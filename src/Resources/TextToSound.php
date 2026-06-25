<?php

declare(strict_types=1);

namespace RunApi\Elevenlabs\Resources;

use RunApi\Core\Http\HttpClient;
use RunApi\Core\Models\TaskCreateResponse;
use RunApi\Core\RequestOptions;
use RunApi\Core\Resources\TypedConfiguredResource;
use RunApi\Elevenlabs\Models\AudioTaskResponse;
use RunApi\Elevenlabs\Models\CompletedAudioTaskResponse;
use RunApi\Elevenlabs\Types;

/**
 * Generates sound effects (rain, footsteps, ambience, etc.) from text descriptions, not speech.
 */
readonly class TextToSound extends TypedConfiguredResource
{
    /**
     * Submits an ElevenLabs text-to-sound task and returns immediately with a task id.
     *
     * @param array{
     *   model: string,
     *   text: string,
     *   callback_url?: string,
     *   loop?: bool,
     *   duration_seconds?: int|float,
     *   prompt_influence?: float,
     *   output_format?: string
     * } $params
     */
    public function create(array $params, ?RequestOptions $options = null): TaskCreateResponse
    {
        return parent::create($params, $options);
    }

    /**
     * Fetches the current status of an ElevenLabs text-to-sound task by id.
     */
    public function get(string $id, ?RequestOptions $options = null): AudioTaskResponse
    {
        $response = parent::get($id, $options);

        /** @var AudioTaskResponse $response */
        return $response;
    }

    /**
     * Submits an ElevenLabs text-to-sound task and polls until it completes.
     *
     * @param array{
     *   model: string,
     *   text: string,
     *   callback_url?: string,
     *   loop?: bool,
     *   duration_seconds?: int|float,
     *   prompt_influence?: float,
     *   output_format?: string
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
            '/api/v1/elevenlabs/text_to_sound',
            'elevenlabs/text-to-sound',
            AudioTaskResponse::class,
            CompletedAudioTaskResponse::class,
            Types::TEXT_TO_SOUND_MODELS,
            'text-to-sound',
            AudioTaskResponse::class,
            CompletedAudioTaskResponse::class,
        );
    }
}
