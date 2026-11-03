# PHP AI SDK — Rev.ai

Audio transcription with Rev.ai for the framework-agnostic [PHP AI SDK](https://phpaisdk.com).

## Installation

```bash
composer require aisdk/revai
```

Set `REVAI_ACCESS_TOKEN` in your environment.

## Usage

```php
use AiSdk\Content;
use AiSdk\Generate;
use AiSdk\RevAi;

$result = Generate::transcription(Content::audio(__DIR__.'/meeting.mp3'))
    ->model(RevAi::model('machine'))
    ->run();

echo $result->output->text;
```

## Documentation

[Read the documentation](https://phpaisdk.com/docs/0.x/providers/revai) for configuration, supported features, and more examples.

[Contributing](https://github.com/phpaisdk/.github/blob/main/CONTRIBUTING.md) · [Support](https://github.com/phpaisdk/.github/blob/main/SUPPORT.md)

## License

The MIT License (MIT). See [LICENSE](LICENSE) for details.
