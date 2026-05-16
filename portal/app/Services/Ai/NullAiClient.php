<?php

declare(strict_types=1);

namespace App\Services\Ai;

use App\Contracts\AiVisionClient;

/**
 * No-op AI client for testing — always returns empty extraction.
 */
final class NullAiClient implements AiVisionClient
{
    /**
     * @param  array<string, mixed>  $schema
     * @return array{data: array<string, mixed>, confidence: array<string, mixed>, tokens: array{prompt: int, completion: int}, cost_inr: float}
     */
    public function extract(string $base64File, string $mimeType, array $schema): array
    {
        return [
            'data' => [],
            'confidence' => [],
            'tokens' => ['prompt' => 0, 'completion' => 0],
            'cost_inr' => 0.0,
        ];
    }
}
