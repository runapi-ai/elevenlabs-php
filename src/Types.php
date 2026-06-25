<?php

declare(strict_types=1);

namespace RunApi\Elevenlabs;

/**
 * Constants for model slugs supported by the ElevenLabs PHP SDK.
 */
final class Types
{
    /** @var list<string> */
    public const TEXT_TO_SPEECH_MODELS = ['text-to-speech-multilingual-v2', 'text-to-speech-turbo-v2.5'];

    /** @var list<string> */
    public const SPEECH_TO_TEXT_MODELS = ['speech-to-text'];

    /** @var list<string> */
    public const ISOLATE_AUDIO_MODELS = ['audio-isolation'];

    /** @var list<string> */
    public const TEXT_TO_DIALOGUE_MODELS = ['text-to-dialogue-v3'];

    /** @var list<string> */
    public const TEXT_TO_SOUND_MODELS = ['sound-effect-v2'];

    private function __construct()
    {
    }
}
