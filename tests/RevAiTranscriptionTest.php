<?php

declare(strict_types=1);
use AiSdk\Content;
use AiSdk\Generate;
use AiSdk\RevAi;
use AiSdk\Support\Sdk;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

afterEach(function (): void {
    Generate::reset();
    RevAi::reset();
});
it('submits and polls an RevAi transcription', function (): void {
    $client = new class implements ClientInterface {
        public array $requests = [];
        private array $responses;
        public function __construct()
        {
            $this->responses = [['id' => 'tr_1'], ['id' => 'tr_1', 'status' => 'transcribed', 'language' => 'en', 'duration_seconds' => 1.2], ['monologues' => [['speaker' => 0, 'elements' => [['type' => 'text', 'value' => 'Hello'], ['type' => 'text', 'value' => 'world']]]]]];
        } public function sendRequest(RequestInterface $request): ResponseInterface
        {
            $this->requests[] = $request;
            return new Response(200, ['Content-Type' => 'application/json'], json_encode(array_shift($this->responses)));
        }
    };
    $factory = new Psr17Factory();
    Generate::configure(new Sdk($client, $factory, $factory));
    RevAi::create(['apiKey' => 'revai-test', 'pollingIntervalMs' => 0]);
    $result = Generate::transcription()->model(RevAi::model('machine'))->audio(Content::audio('https://example.com/audio.mp3'))->run();
    expect($result->output->text)->toBe('Hello world')->and((string) $client->requests[0]->getUri())->toBe('https://api.rev.ai/speechtotext/v1/jobs')->and((string) $client->requests[1]->getUri())->toBe('https://api.rev.ai/speechtotext/v1/jobs/tr_1')->and((string) $client->requests[2]->getUri())->toBe('https://api.rev.ai/speechtotext/v1/jobs/tr_1/transcript');
});
