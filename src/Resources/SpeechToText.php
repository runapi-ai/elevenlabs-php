<?php

declare(strict_types=1);

namespace RunApi\Elevenlabs\Resources;

use RunApi\Core\Http\HttpClient;
use RunApi\Core\Models\TaskCreateResponse;
use RunApi\Core\RequestOptions;
use RunApi\Core\Resources\TypedConfiguredResource;
use RunApi\Elevenlabs\Models\CompletedSpeechToTextResponse;
use RunApi\Elevenlabs\Models\SpeechToTextResponse;

/**
 * Transcribes audio with optional speaker diarization and audio event tagging (laughter, applause).
 */
readonly class SpeechToText extends TypedConfiguredResource
{
    /**
     * Submits an ElevenLabs speech-to-text task and returns immediately with a task id.
     *
     * @param array{
     *   model: string,
     *   source_audio_url: string,
     *   callback_url?: string,
     *   language_code?: string,
     *   tag_audio_events?: bool,
     *   diarize?: bool
     * } $params
     */
    public function create(array $params, ?RequestOptions $options = null): TaskCreateResponse
    {
        return parent::create($params, $options);
    }

    /**
     * Fetches the current status of an ElevenLabs speech-to-text task by id.
     */
    public function get(string $id, ?RequestOptions $options = null): SpeechToTextResponse
    {
        $response = parent::get($id, $options);

        /** @var SpeechToTextResponse $response */
        return $response;
    }

    /**
     * Submits an ElevenLabs speech-to-text task and polls until it completes.
     *
     * @param array{
     *   model: string,
     *   source_audio_url: string,
     *   callback_url?: string,
     *   language_code?: string,
     *   tag_audio_events?: bool,
     *   diarize?: bool
     * } $params
     */
    public function run(array $params, ?RequestOptions $options = null): CompletedSpeechToTextResponse
    {
        $response = parent::run($params, $options);

        /** @var CompletedSpeechToTextResponse $response */
        return $response;
    }

    /**
     * Create the resource using the shared RunAPI HTTP transport.
     */
    public static function fromHttp(HttpClient $http): self
    {
        return new self(
            $http,
            '/api/v1/elevenlabs/speech_to_text',
            SpeechToTextResponse::class,
            CompletedSpeechToTextResponse::class,
            'speech-to-text',
            SpeechToTextResponse::class,
            CompletedSpeechToTextResponse::class,
        );
    }
}
