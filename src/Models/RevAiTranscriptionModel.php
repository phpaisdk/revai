<?php

declare(strict_types=1);

namespace AiSdk\RevAi\Models;

use AiSdk\ContentSource;
use AiSdk\Contracts\BaseModel;
use AiSdk\Contracts\TranscriptionModelInterface;
use AiSdk\Exceptions\InvalidArgumentException;
use AiSdk\Exceptions\InvalidResponseException;
use AiSdk\Requests\TranscriptionRequest;
use AiSdk\Responses\TranscriptionResponse;
use AiSdk\Results\TranscriptData;
use AiSdk\RevAi\RevAiOptions;
use AiSdk\Support\Usage;
use AiSdk\Utils\Support\Url;

final class RevAiTranscriptionModel extends BaseModel implements TranscriptionModelInterface
{
    public function __construct(private readonly string $modelId, private readonly RevAiOptions $options) {} public function provider(): string
    {
        return RevAiOptions::PROVIDER_NAME;
    } public function modelId(): string
    {
        return $this->modelId;
    } public function transcribe(TranscriptionRequest $r): TranscriptionResponse
    {
        if ($r->audio->source() !== ContentSource::Url) {
            throw new InvalidArgumentException('Rev AI requires a public audio URL.');
        }$run = $this->runner($this->options->sdk);
        $job = $run->postJson(Url::joinPath($this->options->baseUrl, '/jobs'), array_replace(['source_config' => ['url' => $r->audio->url()]], $r->providerOptionsFor($this->provider())), $this->options->authHeaders(), $this->provider());
        $id = (string) ($job['id'] ?? '');
        do {
            $state = $run->getJson(Url::joinPath($this->options->baseUrl, '/jobs/' . $id), $this->options->authHeaders(), $this->provider());
        } while (($state['status'] ?? '') === 'in_progress');
        if (($state['status'] ?? '') !== 'transcribed') {
            throw new InvalidResponseException('Rev AI transcription failed.');
        }$data = $run->getJson(Url::joinPath($this->options->baseUrl, '/jobs/' . $id . '/transcript'), $this->options->authHeaders(), $this->provider());
        $text = '';
        foreach ((array) ($data['monologues'] ?? []) as $m) {
            foreach ((array) ($m['elements'] ?? []) as $e) {
                $text .= ($e['type'] ?? '') === 'text' ? ((string) $e['value'] . ' ') : '';
            }
        }return new TranscriptionResponse(new TranscriptData(trim($text), (string) ($state['language'] ?? ''), (float) ($state['duration_seconds'] ?? 0)), new Usage(), $data, ['revai' => ['id' => $id]]);
    }
}
