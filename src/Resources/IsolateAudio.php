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
 * Separates vocals from background noise, returning a clean vocal-only audio track.
 */
readonly class IsolateAudio extends TypedConfiguredResource
{
    /**
     * Submits an ElevenLabs audio isolation task and returns immediately with a task id.
     *
     * @param array{
     *   model: string,
     *   source_audio_url: string,
     *   callback_url?: string,
     *   prompt?: string
     * } $params
     */
    public function create(array $params, ?RequestOptions $options = null): TaskCreateResponse
    {
        return parent::create($params, $options);
    }

    /**
     * Fetches the current status of an ElevenLabs audio isolation task by id.
     */
    public function get(string $id, ?RequestOptions $options = null): AudioTaskResponse
    {
        $response = parent::get($id, $options);

        /** @var AudioTaskResponse $response */
        return $response;
    }

    /**
     * Submits an ElevenLabs audio isolation task and polls until it completes.
     *
     * @param array{
     *   model: string,
     *   source_audio_url: string,
     *   callback_url?: string,
     *   prompt?: string
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
            '/api/v1/elevenlabs/isolate_audio',
            'elevenlabs/isolate-audio',
            AudioTaskResponse::class,
            CompletedAudioTaskResponse::class,
            Types::ISOLATE_AUDIO_MODELS,
            'isolate-audio',
            AudioTaskResponse::class,
            CompletedAudioTaskResponse::class,
        );
    }
}
