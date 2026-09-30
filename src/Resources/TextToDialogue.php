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
 * Generates multi-speaker audio where each dialogue line can use a different voice.
 */
readonly class TextToDialogue extends TypedConfiguredResource
{
    /**
     * Submits an ElevenLabs text-to-dialogue task and returns immediately with a task id.
     *
     * @param array{
     *   model: string,
     *   dialogue: list<array{text: string, voice: string}>,
     *   callback_url?: string,
     *   stability?: float,
     *   language_code?: string
     * } $params
     */
    public function create(array $params, ?RequestOptions $options = null): TaskCreateResponse
    {
        return parent::create($params, $options);
    }

    /**
     * Fetches the current status of an ElevenLabs text-to-dialogue task by id.
     */
    public function get(string $id, ?RequestOptions $options = null): AudioTaskResponse
    {
        $response = parent::get($id, $options);

        /** @var AudioTaskResponse $response */
        return $response;
    }

    /**
     * Submits an ElevenLabs text-to-dialogue task and polls until it completes.
     *
     * @param array{
     *   model: string,
     *   dialogue: list<array{text: string, voice: string}>,
     *   callback_url?: string,
     *   stability?: float,
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
            '/api/v1/elevenlabs/text_to_dialogue',
            AudioTaskResponse::class,
            CompletedAudioTaskResponse::class,
            'text-to-dialogue',
            AudioTaskResponse::class,
            CompletedAudioTaskResponse::class,
        );
    }
}
