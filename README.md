# RevAi provider for PHP AI SDK

```bash
composer require aisdk/revai
```

```php
use AiSdk\RevAi;
use AiSdk\Content;
use AiSdk\Generate;

RevAi::create(['apiKey' => $_ENV['REVAI_ACCESS_TOKEN']]);

$result = Generate::transcription()
    ->model(RevAi::model('universal-3-pro'))
    ->audio(Content::audio('https://example.com/audio.mp3'))
    ->run();
```

The provider uploads non-URL audio, submits it to RevAi’s `/v2/transcript` endpoint, and polls until completion. Provider options map directly to RevAi’s native transcription request fields. See [RevAi’s API reference](https://www.revai.com/docs/api-reference/transcripts/submit).
