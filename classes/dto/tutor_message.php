<?php
/**
 * Unified tutor message DTO and API vocabulary.
 *
 * @package    local_dixeo
 * @copyright  2026 Edunao SAS (contact@edunao.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_dixeo\dto;

defined('MOODLE_INTERNAL') || die();

/**
 * One structure for all tutor POST /v1/tutor/messages payloads.
 */
class tutor_message {

    public const ROLE_USER = 'user';
    public const ROLE_SYSTEM = 'system';
    public const ROLE_ASSISTANT = 'assistant';

    public const MODE_NORMAL = 'normal';
    public const MODE_GUIDE = 'guide';
    public const MODE_QUIZ = 'quiz';
    public const MODE_TEACH = 'teach';

    /** @var string One of ROLE_* constants. */
    public string $role;

    /** @var string Visible chat text (required for user/assistant). */
    public string $message;

    /** @var array Context object (always required). */
    public array $context;

    /** @var string|null Course instructions markdown; auto-filled for user when null. */
    public ?string $instructions;

    /** @var bool For system messages: whether the remote should enqueue a reply. */
    public bool $requireresponse;

    /**
     * @param string $role
     * @param string $message
     * @param array $context
     * @param string|null $instructions
     * @param bool $requireresponse
     */
    public function __construct(
        string $role,
        string $message,
        array $context,
        ?string $instructions = null,
        bool $requireresponse = true
    ) {
        $this->role = self::normalize_role($role);
        $this->message = $message;
        $this->context = $context;
        $this->instructions = $instructions;
        $this->requireresponse = $requireresponse;
    }

    /**
     * System message with opaque structured context.
     *
     * @param array $context
     * @param string $message Optional visible text.
     * @param string|null $instructions
     * @param bool $requireresponse
     * @return self
     */
    public static function system(
        array $context,
        string $message = '',
        ?string $instructions = null,
        bool $requireresponse = true
    ): self {
        return new self(self::ROLE_SYSTEM, $message, $context, $instructions, $requireresponse);
    }

    /**
     * @return string[]
     */
    public static function roles(): array {
        return [self::ROLE_USER, self::ROLE_SYSTEM, self::ROLE_ASSISTANT];
    }

    /**
     * @return string[]
     */
    public static function modes(): array {
        return [self::MODE_NORMAL, self::MODE_GUIDE, self::MODE_QUIZ, self::MODE_TEACH];
    }

    /**
     * @param string|null $role
     * @return string
     */
    public static function normalize_role(?string $role): string {
        $role = strtolower(trim((string) $role));
        return in_array($role, self::roles(), true) ? $role : self::ROLE_USER;
    }

    /**
     * @param string|null $mode
     * @return string
     */
    public static function normalize_mode(?string $mode): string {
        $mode = strtolower(trim((string) $mode));
        return in_array($mode, self::modes(), true) ? $mode : self::MODE_NORMAL;
    }

    /**
     * @throws \invalid_parameter_exception
     */
    public function validate(): void {
        if ($this->context === []) {
            throw new \invalid_parameter_exception('context object is required');
        }

        if ($this->role === self::ROLE_USER || $this->role === self::ROLE_ASSISTANT) {
            if (trim($this->message) === '') {
                throw new \invalid_parameter_exception('message is required for user and assistant roles');
            }
        }
    }

}
