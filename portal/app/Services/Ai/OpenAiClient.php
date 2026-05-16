<?php

declare(strict_types=1);

namespace App\Services\Ai;

use App\Contracts\AiVisionClient;
use OpenAI\Laravel\Facades\OpenAI;

final class OpenAiClient implements AiVisionClient
{
    /**
     * @param  array<string, mixed>  $schema
     * @return array{data: array<string, mixed>, confidence: array<string, mixed>, tokens: array{prompt: int, completion: int}, cost_inr: float}
     */
    public function extract(string $base64File, string $mimeType, array $schema): array
    {
        $model = config('openai.model', 'gpt-4o-mini');

        $prompt = 'Extract the following structured data from this document image. '.
            'Return a JSON object with two keys: "data" containing the extracted fields, '.
            'and "confidence" with float values 0-1 for each field. '.
            'Fields to extract: '.json_encode(array_keys($schema));

        $dataUrl = "data:{$mimeType};base64,{$base64File}";

        $response = OpenAI::chat()->create([
            'model' => $model,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => [
                        ['type' => 'text', 'text' => $prompt],
                        ['type' => 'image_url', 'image_url' => ['url' => $dataUrl]],
                    ],
                ],
            ],
            'response_format' => ['type' => 'json_object'],
        ]);

        $text = $response->choices[0]->message->content ?? '{}';

        /** @var array{data?: array<string, mixed>, confidence?: array<string, mixed>} $parsed */
        $parsed = json_decode($text, true) ?? [];

        $promptTokens = $response->usage->promptTokens ?? 0;
        $completionTokens = $response->usage->completionTokens ?? 0;
        $totalTokens = $promptTokens + $completionTokens;

        // gpt-4o-mini pricing: ~$0.00015/1K input, $0.0006/1K output
        $costUsd = ($promptTokens / 1000 * 0.00015) + ($completionTokens / 1000 * 0.0006);
        $costInr = $costUsd * 84;

        return [
            'data' => $parsed['data'] ?? [],
            'confidence' => $parsed['confidence'] ?? [],
            'tokens' => ['prompt' => $promptTokens, 'completion' => $completionTokens],
            'cost_inr' => $costInr,
        ];
    }
}
