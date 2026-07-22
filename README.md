# aisdk/revai

<a href="https://github.com/phpaisdk/revai/actions"><img alt="GitHub Workflow Status" src="https://img.shields.io/github/actions/workflow/status/phpaisdk/revai/tests.yml?branch=main&label=Tests"></a>
<a href="https://packagist.org/packages/aisdk/revai"><img alt="Latest Version" src="https://img.shields.io/packagist/v/aisdk/revai"></a>
<a href="https://packagist.org/packages/aisdk/revai"><img alt="License" src="https://img.shields.io/packagist/l/aisdk/revai"></a>

------

Official Rev AI provider for the PHP AI SDK. Uses Rev AI's native asynchronous speech-to-text API.

## Installation

```bash
composer require aisdk/revai
```

## Basic Usage

```php
use AiSdk\Content;
use AiSdk\Generate;
use AiSdk\RevAi;

RevAi::create(['apiKey' => $_ENV['REVAI_ACCESS_TOKEN']]);

$result = Generate::transcription()
    ->model(RevAi::model('machine'))
    ->audio(Content::audio('https://example.com/audio.mp3'))
    ->run();
```

## Configuration

| Variable | Description | Default |
| --- | --- | --- |
| `REVAI_ACCESS_TOKEN` | Access token for authentication | Required |
| `REVAI_BASE_URL` | Base URL for API requests | `https://api.rev.ai/speechtotext/v1` |

## Supported Capabilities

| Capability | Support |
| --- | --- |
| Asynchronous transcription | Native `/jobs` API |
| Public audio URLs | Native |
| Local files | Not exposed by the package |

The package submits a job, polls its status, and retrieves the transcript. Use `providerOptions('revai', [...])` for native job fields. Supply a public audio URL; local uploads are not currently exposed by this package.

## Documentation

- [Rev AI speech-to-text API documentation](https://docs.rev.ai/api/asynchronous/)
- [PHP AI SDK documentation](https://phpaisdk.com/docs/revai)
