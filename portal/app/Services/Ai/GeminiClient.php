<?php

declare(strict_types=1);

namespace App\Services\Ai;

use App\Contracts\AiVisionClient;
use GuzzleHttp\Client;
use RuntimeException;

/**
 * Gemini-based AI vision client for document extraction.
 * Uses the OpenAI-compatible API via gemini-php/laravel if available.
 */
final class GeminiClient implements AiVisionClient
{
    /**
     * @param  array<string, mixed>  $schema
     * @return array{data: array<string, mixed>, confidence: array<string, mixed>, tokens: array{prompt: int, completion: int}, cost_inr: float}
     */
    public function extract(string $base64File, string $mimeType, array $schema): array
    {
        $apiKey = config('services.gemini.api_key');

        if (empty($apiKey)) {
            throw new RuntimeException('Gemini API key not configured. Set GEMINI_API_KEY in .env');
        }

        $model = config('services.gemini.model', 'gemini-2.0-flash');

        $prompt = 'Extract the following structured data from this document. '.
            'Return a JSON object with these fields: '.json_encode(array_keys($schema)).'. '.
            'Also return a confidence object with the same keys and float values 0-1. '.
            'Format: {"data": {...}, "confidence": {...}}';

        $client = new Client;
        $response = $client->post(
            "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}",
            [
                'json' => [
                    'contents' => [[
                        'parts' => [
                            ['text' => $prompt],
                            ['inline_data' => ['mime_type' => $mimeType, 'data' => $base64File]],
                        ],
                    ]],
                    'generationConfig' => ['response_mime_type' => 'application/json'],
                ],
            ],
        );

        /** @var array<string, mixed> $body */
        $body = json_decode((string) $response->getBody(), true);

        /** @var string $text */
        $text = $body['candidates'][0]['content']['parts'][0]['text'] ?? '{}';

        /** @var array{data?: array<string, mixed>, confidence?: array<string, mixed>} $parsed */
        $parsed = json_decode($text, true) ?? [];

        $promptTokens = (int) ($body['usageMetadata']['promptTokenCount'] ?? 0);
        $completionTokens = (int) ($body['usageMetadata']['candidatesTokenCount'] ?? 0);
        $totalTokens = $promptTokens + $completionTokens;
        $costInr = ($totalTokens / 1000) * 0.075;

        return [
            'data' => $parsed['data'] ?? [],
            'confidence' => $parsed['confidence'] ?? [],
            'tokens' => ['prompt' => $promptTokens, 'completion' => $completionTokens],
            'cost_inr' => $costInr,
        ];
    }
}
