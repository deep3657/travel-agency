<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\ExtractionJob;
use App\Services\Ai\GeminiClient;
use App\Services\Ai\NullAiClient;
use App\Services\Ai\OpenAiClient;
use App\Services\Ai\Schemas\FlightSchema;
use App\Services\Ai\Schemas\HotelSchema;
use App\Services\AiBudgetTracker;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Throwable;

class ExtractAction implements ShouldQueue
{
    use Queueable;

    public int $tries = 1;

    public int $timeout = 90;

    public function __construct(public readonly int $extractionJobId) {}

    public function handle(AiBudgetTracker $budget): void
    {
        $job = ExtractionJob::with('supplierDocument')->findOrFail($this->extractionJobId);

        $job->update([
            'status' => 'processing',
            'request_started_at' => now(),
        ]);

        $sd = $job->supplierDocument;

        $schema = match ($sd->doc_type) {
            'flight' => FlightSchema::SCHEMA,
            'hotel' => HotelSchema::SCHEMA,
            default => [],
        };

        $filePath = storage_path('app/private/'.$sd->storage_path);

        if (! file_exists($filePath)) {
            $job->update([
                'status' => 'failed',
                'error_code' => 'FILE_NOT_FOUND',
                'error_message' => "File not found: {$filePath}",
            ]);

            return;
        }

        $base64 = base64_encode((string) file_get_contents($filePath));

        $result = null;
        $provider = null;

        try {
            if (! empty(config('services.gemini.api_key'))) {
                $client = new GeminiClient;
                $result = $client->extract($base64, $sd->mime, $schema);
                $provider = 'gemini';
            }
        } catch (Throwable $e) {
            // Fall through to OpenAI
        }

        if ($result === null) {
            try {
                if (! empty(config('openai.api_key'))) {
                    $client = new OpenAiClient;
                    $result = $client->extract($base64, $sd->mime, $schema);
                    $provider = 'openai';
                }
            } catch (Throwable $e) {
                $job->update([
                    'status' => 'failed',
                    'error_code' => 'AI_ERROR',
                    'error_message' => $e->getMessage(),
                    'request_completed_at' => now(),
                ]);

                return;
            }
        }

        if ($result === null) {
            $nullClient = new NullAiClient;
            $result = $nullClient->extract($base64, $sd->mime, $schema);
            $provider = 'null';
        }

        $startTime = $job->request_started_at;
        $responseMs = $startTime ? (int) ($startTime->diffInMilliseconds(now())) : null;

        $job->update([
            'status' => 'completed',
            'provider' => $provider,
            'request_completed_at' => now(),
            'response_time_ms' => $responseMs,
            'prompt_tokens' => $result['tokens']['prompt'],
            'completion_tokens' => $result['tokens']['completion'],
            'estimated_cost_inr' => $result['cost_inr'],
            'extracted_json' => $result['data'],
            'confidence_json' => $result['confidence'],
        ]);

        $budget->recordCost($job->refresh());
    }
}
