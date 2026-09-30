# ElevenLabs PHP SDK for RunAPI

[![Packagist](https://img.shields.io/packagist/v/runapi-ai/elevenlabs)](https://packagist.org/packages/runapi-ai/elevenlabs)
[![License](https://img.shields.io/github/license/runapi-ai/elevenlabs-php)](https://github.com/runapi-ai/elevenlabs-php/blob/main/LICENSE)

The ElevenLabs PHP SDK is the Composer package for ElevenLabs on RunAPI. Use it when your PHP application needs associative-array request bodies, task status lookup, polling helpers, file helpers, and consistent RunAPI errors.

## Install

```bash
composer require runapi-ai/elevenlabs
```

## Quick start

```php
<?php

require __DIR__ . "/vendor/autoload.php";

use RunApi\Elevenlabs\ElevenlabsClient;

$client = new ElevenlabsClient(); // reads RUNAPI_API_KEY

$task = $client->textToSpeech->create([
    'model' => 'text-to-speech-multilingual-v2',
    'text' => 'Welcome to the launch briefing.',
]);

$status = $client->textToSpeech->get($task->id);

$transcript = $client->speechToText->run([
    'model' => 'speech-to-text',
    'source_audio_url' => 'https://cdn.runapi.ai/public/samples/voice.mp3',
]);

$result = $client->textToSpeech->run([
    'model' => 'text-to-speech-multilingual-v2',
    'text' => 'A concise product update for the team.',
]);

echo $result->audios[0]->url . PHP_EOL;
echo $transcript->text . PHP_EOL;
```

Use `create()` to submit a task and return quickly, `get()` to fetch the latest task state, and `run()` when a script should create and poll until completion. In web request handlers, prefer `create()` plus webhook or later `get()` polling so a worker is not held open.

Returned file URLs are temporary. Download and store generated files in your own durable storage within the retention window.

All SDK exceptions inherit from `RunApi\Core\Errors\RunApiException`, including API, authentication, rate limit, task failure, and task timeout errors.

## Links

- Model page: https://runapi.ai/models/elevenlabs
- SDK docs: https://runapi.ai/docs/resources/sdks
- Product docs: https://runapi.ai/docs/api/elevenlabs/text-to-speech
- Pricing and rate limits: https://runapi.ai/models/elevenlabs/text-to-speech-turbo-v2.5
- Full catalog: https://runapi.ai/models
- GitHub repository: https://github.com/runapi-ai/elevenlabs-php
- Multi-language SDK repository: https://github.com/runapi-ai/elevenlabs-sdk

## License

Licensed under the Apache License, Version 2.0.
