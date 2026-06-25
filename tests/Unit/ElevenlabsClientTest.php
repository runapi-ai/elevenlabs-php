<?php

declare(strict_types=1);

namespace RunApi\Elevenlabs\Tests\Unit;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use RunApi\Core\ClientOptions;
use RunApi\Core\Errors\ValidationException;
use RunApi\Core\Tests\Fixtures\QueueHttpClient;
use RunApi\Elevenlabs\ElevenlabsClient;
use RunApi\Elevenlabs\Models\CompletedAudioTaskResponse;
use RunApi\Elevenlabs\Models\CompletedSpeechToTextResponse;
use RunApi\Elevenlabs\Resources\IsolateAudio;
use RunApi\Elevenlabs\Resources\SpeechToText;
use RunApi\Elevenlabs\Resources\TextToDialogue;
use RunApi\Elevenlabs\Resources\TextToSound;
use RunApi\Elevenlabs\Resources\TextToSpeech;

final class ElevenlabsClientTest extends TestCase
{
    public function testExposesTypedResources(): void
    {
        $client = new ElevenlabsClient(new ClientOptions(apiKey: 'k', httpClient: new QueueHttpClient([]), maxRetries: 0));

        self::assertInstanceOf(TextToSpeech::class, $client->textToSpeech);
        self::assertInstanceOf(SpeechToText::class, $client->speechToText);
        self::assertInstanceOf(IsolateAudio::class, $client->isolateAudio);
        self::assertInstanceOf(TextToDialogue::class, $client->textToDialogue);
        self::assertInstanceOf(TextToSound::class, $client->textToSound);
    }

    public function testCreatePostsCompactedBodyToCorrectPath(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_1"}'),
        ]);
        $client = new ElevenlabsClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $task = $client->textToSpeech->create([
            'model' => 'text-to-speech-multilingual-v2',
            'text' => 'Hello from RunAPI',
            'callback_url' => '',
            'seed' => null,
        ]);

        $body = json_decode((string) $transport->requests[0]->getBody(), true, flags: JSON_THROW_ON_ERROR);

        self::assertSame('task_1', $task->id);
        self::assertSame('/api/v1/elevenlabs/text_to_speech', $transport->requests[0]->getUri()->getPath());
        self::assertSame('text-to-speech-multilingual-v2', $body['model']);
        self::assertArrayNotHasKey('callback_url', $body);
        self::assertArrayNotHasKey('seed', $body);
    }

    public function testRunReturnsTypedCompletedResponseAndPreservesUnknownFields(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_1"}'),
            new Response(200, [], '{"id":"task_1","status":"completed","audios":[{"url":"https://file.runapi.ai/result"}],"extra_field":"kept"}'),
        ]);
        $client = new ElevenlabsClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $result = $client->textToSpeech->run([
            'model' => 'text-to-speech-multilingual-v2',
            'text' => 'Hello from RunAPI',
        ]);

        self::assertInstanceOf(CompletedAudioTaskResponse::class, $result);
        self::assertSame('https://file.runapi.ai/result', $result->audios[0]->url);
        self::assertSame('kept', $result->toArray()['extra_field']);
        self::assertSame('/api/v1/elevenlabs/text_to_speech/task_1', $transport->requests[1]->getUri()->getPath());
    }

    public function testCompletedResponseRequiresResultFiles(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_1"}'),
            new Response(200, [], '{"id":"task_1","status":"completed"}'),
        ]);
        $client = new ElevenlabsClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('audios is required');

        $client->textToSpeech->run([
            'model' => 'text-to-speech-multilingual-v2',
            'text' => 'Hello from RunAPI',
        ]);
    }

    public function testRejectsInvalidContractEnum(): void
    {
        $client = new ElevenlabsClient(new ClientOptions(apiKey: 'k', httpClient: new QueueHttpClient([]), maxRetries: 0));

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('stability must be one of the allowed values');

        $client->textToDialogue->create([
        'model' => 'text-to-dialogue-v3',
        'dialogue' => [['text' => 'Hello there', 'voice' => 'Narrator']],
        'stability' => 0.25,
        ]);
    }

    public function testSecondaryResourceUsesItsOwnPath(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_2"}'),
        ]);
        $client = new ElevenlabsClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $client->speechToText->create([
            'model' => 'speech-to-text',
            'source_audio_url' => 'https://cdn.runapi.ai/public/samples/voice.mp3',
        ]);

        self::assertSame('/api/v1/elevenlabs/speech_to_text', $transport->requests[0]->getUri()->getPath());
    }

    public function testSpeechToTextRunReturnsTranscript(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_3"}'),
            new Response(200, [], '{"id":"task_3","status":"completed","text":"Hello world","extra_field":"kept"}'),
        ]);
        $client = new ElevenlabsClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $result = $client->speechToText->run([
            'model' => 'speech-to-text',
            'source_audio_url' => 'https://cdn.runapi.ai/public/samples/voice.mp3',
            'diarize' => true,
        ]);

        self::assertInstanceOf(CompletedSpeechToTextResponse::class, $result);
        self::assertSame('Hello world', $result->text);
        self::assertSame('kept', $result->toArray()['extra_field']);
        self::assertSame('/api/v1/elevenlabs/speech_to_text/task_3', $transport->requests[1]->getUri()->getPath());
    }
}
