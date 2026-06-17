<?php
/**
 * Resolve and localize generation output language for AI instructions.
 *
 * @package    local_dixeo
 * @copyright  2026 Edunao SAS (contact@edunao.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_dixeo\service;

defined('MOODLE_INTERNAL') || die();

/**
 * Helpers for tutor generation language selection.
 */
class generation_language_helper {

    /**
     * Default generation language for a user (explicit preference or current UI language).
     *
     * @param int $userid
     * @return string Moodle language code.
     */
    public static function default_for_user(int $userid): string {
        $translations = get_string_manager()->get_list_of_translations();
        $user = \core_user::get_user($userid, 'id,lang', IGNORE_MISSING);
        if ($user && !empty($user->lang) && $user->lang !== 'auto' && isset($translations[$user->lang])) {
            return $user->lang;
        }

        $current = current_language();
        if (isset($translations[$current])) {
            return $current;
        }

        return array_key_first($translations) ?: 'en';
    }

    /**
     * Validate a requested language code against installed Moodle languages.
     *
     * @param string $language Requested language code (empty = user default).
     * @param int $userid User id when falling back to preference.
     * @return string Valid Moodle language code.
     */
    public static function resolve(string $language, int $userid = 0): string {
        $translations = get_string_manager()->get_list_of_translations();
        $language = clean_param(trim($language), PARAM_LANG);
        if ($language !== '' && isset($translations[$language])) {
            return $language;
        }

        if ($userid > 0) {
            return self::default_for_user($userid);
        }

        $current = current_language();
        return isset($translations[$current]) ? $current : (array_key_first($translations) ?: 'en');
    }

    /**
     * Human-readable language name for a Moodle language code.
     *
     * @param string $langcode
     * @return string
     */
    public static function display_name(string $langcode): string {
        $translations = get_string_manager()->get_list_of_translations();
        return $translations[$langcode] ?? $langcode;
    }

    /**
     * Fetch a local_dixeo string in a specific language.
     *
     * @param string $identifier
     * @param string|object|null $a
     * @param string $lang
     * @return string
     */
    public static function get_string(string $identifier, $a, string $lang): string {
        return get_string_manager()->get_string($identifier, 'local_dixeo', $a, $lang);
    }

    /**
     * Append the mandatory output-language line to generation instructions.
     *
     * @param string $instructions
     * @param string $lang Moodle language code for generated content.
     * @return string
     */
    public static function append_output_language_instruction(string $instructions, string $lang): string {
        $langname = self::display_name($lang);
        $line = self::get_string('generation_output_language', (object) [
            'language' => $langname,
        ], $lang);

        return rtrim($instructions) . "\n\n" . $line;
    }

    /**
     * Build Mustache/AJAX language options for setup panels.
     *
     * @param int $userid
     * @return array{defaultlanguage: string, languages: array<int, array{code: string, name: string, selected: bool}>}
     */
    public static function build_options(int $userid): array {
        $translations = get_string_manager()->get_list_of_translations();
        $default = self::default_for_user($userid);
        $languages = [];

        foreach ($translations as $code => $name) {
            $languages[] = [
                'code' => $code,
                'name' => $name,
                'selected' => $code === $default,
            ];
        }

        return [
            'defaultlanguage' => $default,
            'languages' => $languages,
        ];
    }
}
