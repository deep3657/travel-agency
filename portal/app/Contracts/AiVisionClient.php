<?php

declare(strict_types=1);

namespace App\Contracts;

interface AiVisionClient
{
    /**
     * Extract structured data from a document file.
     *
     * @param  array<string, mixed>  $schema
     * @return array{data: array<string, mixed>, confidence: array<string, mixed>, tokens: array{prompt: int, completion: int}, cost_inr: float}
     */
    public function extract(string $base64File, string $mimeType, array $schema): array;
}
