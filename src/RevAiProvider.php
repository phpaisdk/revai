<?php

declare(strict_types=1);

namespace AiSdk\RevAi;

use AiSdk\Contracts\BaseProvider;
use AiSdk\Contracts\TranscriptionModelInterface;
use AiSdk\Contracts\TranscriptionProviderInterface;
use AiSdk\RevAi\Models\RevAiTranscriptionModel;

final class RevAiProvider extends BaseProvider implements TranscriptionProviderInterface
{
    public function __construct(public readonly RevAiOptions $options) {} public function name(): string
    {
        return RevAiOptions::PROVIDER_NAME;
    } protected function transcriptionModel(string $modelId): TranscriptionModelInterface
    {
        return new RevAiTranscriptionModel($modelId, $this->options);
    }
}
