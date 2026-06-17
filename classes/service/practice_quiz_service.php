<?php
/**
 * Ephemeral practice quiz generation (no Moodle module creation).
 *
 * @package    local_dixeo
 * @copyright  2026 Edunao SAS (contact@edunao.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_dixeo\service;

use local_dixeo\api\exception\api_exception;
use local_dixeo\context\course_context_builder;
use local_dixeo\dsl\dsl_exception;
use local_dixeo\dto\operation_result;

defined('MOODLE_INTERNAL') || die();

/**
 * Service for tutor practice quiz generation jobs.
 */
class practice_quiz_service {

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
     * Submit a practice quiz generation job from tutor setup panel values.
     *
     * @param int $courseid
     * @param string $scope course|section|activity
     * @param int $sectionnum Section number when scope is section.
     * @param int $cmid Course module id when scope is activity.
     * @param int $count Number of questions (3-10).
     * @param string $difficulty easy|medium|hard
     * @param string $topictitle Human-readable topic label.
     * @param string $language Moodle language code for generated content.
     * @return operation_result
     * @throws api_exception
     */
    public function submit_from_setup(
        int $courseid,
        string $scope,
        int $sectionnum = 0,
        int $cmid = 0,
        int $count = 5,
        string $difficulty = 'medium',
        string $topictitle = '',
        string $language = ''
    ): operation_result {
        $scope = $this->normalize_scope($scope);
        $contextmarkdown = $this->build_context(
            $courseid,
            $scope,
            $sectionnum > 0 ? $sectionnum : null,
            $cmid > 0 ? $cmid : null
        );
        $instructions = $this->build_instructions(
            $count,
            $difficulty,
            $scope,
            $topictitle,
            $language
        );

        return $this->submit_job($courseid, $instructions, $contextmarkdown);
    }

    /**
     * Submit a simplequiz2 generation job for ephemeral practice use.
     *
     * @param int $courseid
     * @param string $instructions
     * @param string $contextmarkdown
     * @return operation_result
     * @throws api_exception
     */
    public function submit_job(int $courseid, string $instructions, string $contextmarkdown): operation_result {
        return $this->modulegeneration->submit_generate_job(
            'simplequiz2',
            $instructions,
            $contextmarkdown,
            $courseid
        );
    }

    /**
     * @return string
     */
    protected function get_course_context_mode(): string {
        return course_context_builder::MODE_ASSESSMENT;
    }

    /**
     * Compose generation instructions from setup panel values.
     *
     * @param int $count Number of questions (3-10).
     * @param string $difficulty easy|medium|hard
     * @param string $scope course|section|activity
     * @param string $scopename Human-readable scope name (course, section, or activity title).
     * @param string $language Moodle language code for generated content.
     * @return string
     */
    public function build_instructions(
        int $count,
        string $difficulty,
        string $scope,
        string $scopename,
        string $language = ''
    ): string {
        global $USER;

        $count = max(3, min(10, $count));
        $difficulty = in_array($difficulty, ['easy', 'medium', 'hard'], true) ? $difficulty : 'medium';
        $scope = $this->normalize_scope($scope);
        $language = generation_language_helper::resolve($language, (int) $USER->id);

        $difficultylabel = match ($difficulty) {
            'easy' => generation_language_helper::get_string('practice_quiz_difficulty_easy', null, $language),
            'hard' => generation_language_helper::get_string('practice_quiz_difficulty_hard', null, $language),
            default => generation_language_helper::get_string('practice_quiz_difficulty_medium', null, $language),
        };

        $instructions = generation_language_helper::get_string('practice_quiz_instructions', (object) [
            'scopedescription' => $this->build_scope_description($scope, $scopename, $language),
            'count' => $count,
            'difficulty' => $difficulty,
            'difficultylabel' => $difficultylabel,
        ], $language);

        return generation_language_helper::append_output_language_instruction($instructions, $language);
    }

    /**
     * Transform a completed generation job into simplequiz2 question JSON.
     *
     * @param string $jobid
     * @param string $fallbacktitle Title if job data has no name.
     * @param int|null $expectedcount Trim excess questions when the API returns too many.
     * @return array{success: bool, error: string, title: string, questions: string}
     * @throws api_exception
     */
    public function finalize_from_job(string $jobid, string $fallbacktitle = '', ?int $expectedcount = null): array {
        $status = $this->jobservice->get_job_status($jobid);

        if (!$status->is_completed()) {
            return $this->practice_quiz_result(
                false,
                '',
                '[]',
                get_string('practice_quiz_error_job_not_completed', 'local_dixeo', (object) [
                    'status' => $status->status,
                ])
            );
        }

        $result = $this->parse_completed_job_result($status->result);
        if ($result === null) {
            return $this->practice_quiz_result(
                false,
                '',
                '[]',
                get_string('practice_quiz_error_invalid_result', 'local_dixeo')
            );
        }

        $moduletype = $result['moduleType'] ?? '';
        if ($moduletype !== 'simplequiz2') {
            return $this->practice_quiz_result(
                false,
                '',
                '[]',
                get_string('practice_quiz_error_wrong_module_type', 'local_dixeo')
            );
        }

        $data = $result['data'] ?? [];
        $rawquestions = $data['questions'] ?? [];
        if (!is_array($rawquestions) || empty($rawquestions)) {
            return $this->practice_quiz_result(
                false,
                '',
                '[]',
                get_string('practice_quiz_error_no_questions', 'local_dixeo')
            );
        }

        try {
            $questions = simplequiz2_question_transformer::transform_job_questions($rawquestions);
        } catch (dsl_exception $e) {
            return $this->practice_quiz_result(false, '', '[]', $e->getMessage());
        }

        $title = trim((string) ($data['name'] ?? ''));
        if ($title === '') {
            $title = trim($fallbacktitle);
        }
        if ($title === '') {
            $title = get_string('practice_quiz_default_title', 'local_dixeo');
        }

        // Re-index as JSON array for embed player.
        $questionlist = array_values($questions);

        if ($expectedcount !== null && $expectedcount > 0) {
            $expectedcount = max(3, min(10, $expectedcount));
            if (count($questionlist) > $expectedcount) {
                $questionlist = array_slice($questionlist, 0, $expectedcount);
            }
        }

        return $this->practice_quiz_result(
            true,
            $title,
            json_encode($questionlist)
        );
    }

    /**
     * Build a practice quiz finalize response array.
     *
     * @param bool $success
     * @param string $title
     * @param string $questions
     * @param string $error
     * @return array{success: bool, error: string, title: string, questions: string}
     */
    private function practice_quiz_result(
        bool $success,
        string $title = '',
        string $questions = '[]',
        string $error = ''
    ): array {
        return [
            'success' => $success,
            'error' => $error,
            'title' => $title,
            'questions' => $questions,
        ];
    }
}
