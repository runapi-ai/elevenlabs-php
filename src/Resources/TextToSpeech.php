<?php

declare(strict_types=1);

namespace RunApi\Elevenlabs\Resources;

use RunApi\Core\Http\HttpClient;
use RunApi\Core\Models\TaskCreateResponse;
use RunApi\Core\RequestOptions;
use RunApi\Core\Resources\TypedConfiguredResource;
use RunApi\Elevenlabs\Models\AudioTaskResponse;
use RunApi\Elevenlabs\Models\CompletedAudioTaskResponse;

/**
 * Generates single-speaker speech with configurable voice, speed, and language. Voice is optional for text-to-speech-turbo-v2.5 (uses a preset) but required for text-to-speech-multilingual-v2.
 */
readonly class TextToSpeech extends TypedConfiguredResource
{
    /**
     * Submits an ElevenLabs text-to-speech task and returns immediately with a task id.
     *
     * @param array{
     *   model: string,
     *   text: string,
     *   callback_url?: string,
     *   voice?: string,
     *   stability?: float,
     *   similarity_boost?: float,
     *   style?: float,
     *   speed?: float,
     *   timestamps?: bool,
     *   previous_text?: string,
     *   next_text?: string,
     *   language_code?: string
     * } $params
     */
    public function create(array $params, ?RequestOptions $options = null): TaskCreateResponse
    {
        return parent::create($params, $options);
    }

    /**
     * Fetches the current status of an ElevenLabs text-to-speech task by id.
     */
    public function get(string $id, ?RequestOptions $options = null): AudioTaskResponse
    {
        $response = parent::get($id, $options);

        /** @var AudioTaskResponse $response */
        return $response;
    }

    /**
     * Submits an ElevenLabs text-to-speech task and polls until it completes.
     *
     * @param array{
     *   model: string,
     *   text: string,
     *   callback_url?: string,
     *   voice?: string,
     *   stability?: float,
     *   similarity_boost?: float,
     *   style?: float,
     *   speed?: float,
     *   timestamps?: bool,
     *   previous_text?: string,
     *   next_text?: string,
     *   language_code?: string
     * } $params
     */
    public function run(array $params, ?RequestOptions $options = null): CompletedAudioTaskResponse
    {
        $response = parent::run($params, $options);

        /** @var CompletedAudioTaskResponse $response */
        return $response;
    }

    /**
     * Create the resource using the shared RunAPI HTTP transport.
     */
    public static function fromHttp(HttpClient $http): self
    {
        return new self(
            $http,
            '/api/v1/elevenlabs/text_to_speech',
            AudioTaskResponse::class,
            CompletedAudioTaskResponse::class,
            'text-to-speech',
            AudioTaskResponse::class,
            CompletedAudioTaskResponse::class,
        );
    }
}
