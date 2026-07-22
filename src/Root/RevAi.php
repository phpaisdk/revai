<?php

declare(strict_types=1);

namespace AiSdk;

use AiSdk\Contracts\Model;
use AiSdk\RevAi\RevAiOptions;
use AiSdk\RevAi\RevAiProvider;

final class RevAi
{
    private static ?RevAiProvider $default = null;
    /** @param array<string,mixed> $config */ public static function create(array $config = []): RevAiProvider
    {
        return self::$default = new RevAiProvider(RevAiOptions::fromArray($config));
    } public static function default(): RevAiProvider
    {
        return self::$default ??= self::create();
    } public static function reset(): void
    {
        self::$default = null;
    } public static function model(string $modelId): Model
    {
        return self::default()->model($modelId);
    }
}
