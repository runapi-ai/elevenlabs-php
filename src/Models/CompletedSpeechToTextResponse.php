<?php

declare(strict_types=1);

namespace RunApi\Elevenlabs\Models;

use RunApi\Core\Support\Payload;

/**
 * Completed completed speech to text response task response returned by run(); outputs are guaranteed present.
 */
readonly class CompletedSpeechToTextResponse extends SpeechToTextResponse
{
    /**
     * Hydrate a completed speech to text response from a RunAPI response object.
     *
     * @param array<string, mixed> $raw
     */
    public static function fromArray(array $raw): self
    {
        return new self(id: Payload::string($raw, 'id'), status: Payload::string($raw, 'status'), error: self::error($raw), text: self::text($raw, required: true), raw: $raw);
    }

    /**
     * Narrow a polled task response after completion has been confirmed.
     */
    public static function fromResponse(SpeechToTextResponse $response): self
    {
        return self::fromArray($response->toArray());
    }
}
