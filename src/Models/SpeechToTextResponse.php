<?php

declare(strict_types=1);

namespace RunApi\Elevenlabs\Models;

use RunApi\Core\Errors\ValidationException;
use RunApi\Core\Models\TaskResponse;
use RunApi\Core\Support\Payload;

/**
 * Async speech to text response task response with lifecycle status and output files.
 */
readonly class SpeechToTextResponse extends TaskResponse
{
    /**
     * Create a speech to text response value object.
     *
     * @param array<string, mixed> $raw
     */
    public function __construct(?string $id, string $status, ?string $error = null, public ?string $text = null, array $raw = [])
    {
        parent::__construct(id: $id, status: $status, error: $error, raw: $raw === [] ? ['id' => $id, 'status' => $status, 'error' => $error, 'text' => $text] : $raw);
    }

    /**
     * Hydrate a speech to text response from a RunAPI response object.
     *
     * @param array<string, mixed> $raw
     */
    public static function fromArray(array $raw): self
    {
        return new self(id: Payload::string($raw, 'id'), status: Payload::string($raw, 'status'), error: self::error($raw), text: self::text($raw), raw: $raw);
    }

    /** @param array<string, mixed> $raw */
    protected static function error(array $raw): ?string
    {
        $value = $raw['error'] ?? null;
        if ($value === null) {
            return null;
        }
        if (!is_string($value)) {
            throw new ValidationException('error must be a string');
        }

        return $value;
    }

    /** @param array<string, mixed> $raw */
    protected static function text(array $raw, bool $required = false): ?string
    {
        $value = $raw['text'] ?? null;
        if ($value === null) {
            if ($required) {
                throw new ValidationException('text is required');
            }

            return null;
        }
        if (!is_string($value)) {
            throw new ValidationException('text must be a string');
        }

        return $value;
    }
}
