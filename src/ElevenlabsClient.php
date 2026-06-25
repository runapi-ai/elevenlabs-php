<?php

declare(strict_types=1);

namespace RunApi\Elevenlabs;

use RunApi\Core\BaseClient;
use RunApi\Core\ClientOptions;
use RunApi\Elevenlabs\Resources\IsolateAudio;
use RunApi\Elevenlabs\Resources\SpeechToText;
use RunApi\Elevenlabs\Resources\TextToDialogue;
use RunApi\Elevenlabs\Resources\TextToSound;
use RunApi\Elevenlabs\Resources\TextToSpeech;

/**
 * Provides speech synthesis, sound effects, transcription, and vocal isolation.
 *
 * Exposes typed model resources plus the universal files and account resources.
 */
final class ElevenlabsClient extends BaseClient
{
    /**
     * Text to speech operations.
     */
    public readonly TextToSpeech $textToSpeech;
    /**
     * Speech to text operations.
     */
    public readonly SpeechToText $speechToText;
    /**
     * Isolate audio operations.
     */
    public readonly IsolateAudio $isolateAudio;
    /**
     * Text to dialogue operations.
     */
    public readonly TextToDialogue $textToDialogue;
    /**
     * Text to sound operations.
     */
    public readonly TextToSound $textToSound;

    /**
     * Create an ElevenLabs client with optional API key, base URL, and transport overrides.
     */
    public function __construct(ClientOptions $options = new ClientOptions())
    {
        parent::__construct($options);
        $this->textToSpeech = TextToSpeech::fromHttp($this->http);
        $this->speechToText = SpeechToText::fromHttp($this->http);
        $this->isolateAudio = IsolateAudio::fromHttp($this->http);
        $this->textToDialogue = TextToDialogue::fromHttp($this->http);
        $this->textToSound = TextToSound::fromHttp($this->http);
    }
}
