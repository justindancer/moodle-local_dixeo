<?php
/**
 * Ephemeral teach-lesson generation (no Moodle module creation).
 *
 * @package    local_dixeo
 * @copyright  2026 Edunao SAS (contact@edunao.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_dixeo\service;

use local_dixeo\api\exception\api_exception;
use local_dixeo\context\course_context_builder;
use local_dixeo\dto\operation_result;

defined('MOODLE_INTERNAL') || die();

/**
 * Service for tutor teach-lesson generation jobs.
 */
class teach_lesson_service {

    use scoped_ephemeral_generation_trait;

    public const SCOPE_COURSE = 'course';
    public const SCOPE_SECTION = 'section';
    public const SCOPE_ACTIVITY = 'activity';

    /** @var module_generation_service */
    private module_generation_service $modulegeneration;

    /** @var job_service */
    private job_service $jobservice;

    /**
     * @param module_generation_service|null $modulegeneration
     * @param job_service|null $jobservice
     */
    public function __construct(
        ?module_generation_service $modulegeneration = null,
        ?job_service $jobservice = null
    ) {
        $this->modulegeneration = $modulegeneration ?? new module_generation_service();
        $this->jobservice = $jobservice ?? new job_service();
    }

    /**
     * Submit a teach lesson generation job from tutor setup panel values.
     *
     * @param int $courseid
     * @param string $scope course|section|activity
     * @param int $sectionnum Section number when scope is section.
     * @param int $cmid Course module id when scope is activity.
     * @param string $topictitle Human-readable topic label.
     * @param string $learnerrequest Learner's free-text learning request (required).
     * @param string $language Moodle language code for generated content.
     * @return operation_result
     * @throws api_exception
     */
    public function submit_from_setup(
        int $courseid,
        string $scope,
        int $sectionnum = 0,
        int $cmid = 0,
        string $topictitle = '',
        string $learnerrequest = '',
        string $language = ''
    ): operation_result {
        $scope = $this->normalize_scope($scope);
        $learnerrequest = trim($learnerrequest);
        if ($learnerrequest === '') {
            throw new \invalid_parameter_exception('Learner request is required');
        }

        $contextmarkdown = $this->build_context(
            $courseid,
            $scope,
            $sectionnum > 0 ? $sectionnum : null,
            $cmid > 0 ? $cmid : null
        );
        $instructions = $this->build_instructions($scope, $topictitle, $learnerrequest, $language);

        return $this->submit_job($courseid, $instructions, $contextmarkdown);
    }

    /**
     * Submit a page generation job for ephemeral teach-lesson use.
     *
     * @param int $courseid
     * @param string $instructions
     * @param string $contextmarkdown
     * @return operation_result
     * @throws api_exception
     */
    public function submit_job(int $courseid, string $instructions, string $contextmarkdown): operation_result {
        return $this->modulegeneration->submit_generate_job(
            'page',
            $instructions,
            $contextmarkdown,
            $courseid
        );
    }

    /**
     * @return string
     */
    protected function get_course_context_mode(): string {
        return course_context_builder::MODE_TEACHING;
    }

    /**
     * Compose generation instructions from setup panel values.
     *
     * @param string $scope course|section|activity
     * @param string $scopename Human-readable scope name (course, section, or activity title).
     * @param string $learnerrequest Learner's free-text learning request.
     * @param string $language Moodle language code for generated content.
     * @return string
     */
    public function build_instructions(
        string $scope,
        string $scopename,
        string $learnerrequest,
        string $language = ''
    ): string {
        global $USER;

        $scope = $this->normalize_scope($scope);
        $language = generation_language_helper::resolve($language, (int) $USER->id);

        $instructions = generation_language_helper::get_string('teach_lesson_instructions', (object) [
            'scopedescription' => $this->build_scope_description($scope, $scopename, $language),
            'learnerrequest' => trim($learnerrequest),
        ], $language);

        return generation_language_helper::append_output_language_instruction($instructions, $language);
    }

    /**
     * Transform a completed generation job into formatted lesson HTML.
     *
     * @param string $jobid
     * @param string $fallbacktitle Title if job data has no name.
     * @param int $courseid Course id for format_text context.
     * @return array{success: bool, error: string, title: string, introhtml: string, contenthtml: string}
     * @throws api_exception
     */
    public function finalize_from_job(string $jobid, string $fallbacktitle = '', int $courseid = 0): array {
        $status = $this->jobservice->get_job_status($jobid);

        if (!$status->is_completed()) {
            return $this->teach_lesson_result(
                false,
                '',
                '',
                '',
                get_string('teach_lesson_error_job_not_completed', 'local_dixeo', (object) [
                    'status' => $status->status,
                ])
            );
        }

        $result = $this->parse_completed_job_result($status->result);
        if ($result === null) {
            return $this->teach_lesson_result(
                false,
                '',
                '',
                '',
                get_string('teach_lesson_error_invalid_result', 'local_dixeo')
            );
        }

        $moduletype = $result['moduleType'] ?? '';
        if ($moduletype !== 'page') {
            return $this->teach_lesson_result(
                false,
                '',
                '',
                '',
                get_string('teach_lesson_error_wrong_module_type', 'local_dixeo')
            );
        }

        $data = $result['data'] ?? [];
        $content = trim((string) ($data['content'] ?? ''));
        if ($content === '') {
            return $this->teach_lesson_result(
                false,
                '',
                '',
                '',
                get_string('teach_lesson_error_no_content', 'local_dixeo')
            );
        }

        $title = trim((string) ($data['name'] ?? ''));
        if ($title === '') {
            $title = trim($fallbacktitle);
        }
        if ($title === '') {
            $title = get_string('teach_lesson_default_title', 'local_dixeo');
        }

        $intro = trim((string) ($data['intro'] ?? ''));
        $formatoptions = ['noclean' => true, 'para' => true, 'filter' => true];

        $introhtml = '';
        if ($intro !== '') {
            $introhtml = trim(format_text($intro, FORMAT_HTML, $formatoptions, $courseid));
        }

        $contenthtml = trim(format_text($content, FORMAT_HTML, $formatoptions, $courseid));

        return $this->teach_lesson_result(true, $title, $introhtml, $contenthtml);
    }

    /**
     * Build a teach lesson finalize response array.
     *
     * @param bool $success
     * @param string $title
     * @param string $introhtml
     * @param string $contenthtml
     * @param string $error
     * @return array{success: bool, error: string, title: string, introhtml: string, contenthtml: string}
     */
    private function teach_lesson_result(
        bool $success,
        string $title = '',
        string $introhtml = '',
        string $contenthtml = '',
        string $error = ''
    ): array {
        return [
            'success' => $success,
            'error' => $error,
            'title' => $title,
            'introhtml' => $introhtml,
            'contenthtml' => $contenthtml,
        ];
    }
}
