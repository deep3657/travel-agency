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
use Illuminate\Support\Facades\Log;
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

        [$base64, $mime, $tempPng] = $this->prepareInput($filePath, $sd->mime);

        try {
            $result = null;
            $provider = null;
            $primary = strtolower((string) config('services.ai.primary', 'gemini'));

            $providers = $primary === 'openai' ? ['openai', 'gemini'] : ['gemini', 'openai'];

            $hasGemini = ! empty(config('services.gemini.api_key'));
            $hasOpenai = ! empty(config('services.openai.api_key')) || ! empty(config('openai.api_key'));

            Log::info('ExtractAction provider config', [
                'job_id' => $job->id,
                'mime' => $mime,
                'primary' => $primary,
                'order' => $providers,
                'has_gemini' => $hasGemini,
                'has_openai' => $hasOpenai,
            ]);

            foreach ($providers as $p) {
                if ($result !== null) {
                    break;
                }
                try {
                    if ($p === 'gemini' && $hasGemini) {
                        $client = new GeminiClient;
                        $result = $client->extract($base64, $mime, $schema);
                        $provider = 'gemini';
                    } elseif ($p === 'openai' && $hasOpenai) {
                        $client = new OpenAiClient;
                        $result = $client->extract($base64, $mime, $schema);
                        $provider = 'openai';
                    } else {
                        Log::info("ExtractAction {$p} skipped (no key configured)", ['job_id' => $job->id]);
                    }
                } catch (Throwable $e) {
                    Log::error("ExtractAction {$p} failed", [
                        'job_id' => $job->id,
                        'mime' => $mime,
                        'error' => mb_substr($e->getMessage(), 0, 500),
                        'class' => $e::class,
                    ]);
                }
            }

            if ($result === null) {
                $nullClient = new NullAiClient;
                $result = $nullClient->extract($base64, $mime, $schema);
                $provider = 'null';
            }
        } finally {
            if ($tempPng !== null && file_exists($tempPng)) {
                @unlink($tempPng);
            }
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

    /**
     * For PDFs, render the first page to PNG via pdftoppm so vision models
     * that don't accept PDFs (e.g. OpenAI gpt-4o-mini) can still process it.
     *
     * @return array{0: string, 1: string, 2: ?string} [base64, mime, temp-path-or-null]
     */
    private function prepareInput(string $filePath, string $originalMime): array
    {
        if (! str_starts_with($originalMime, 'application/pdf')) {
            return [base64_encode((string) file_get_contents($filePath)), $originalMime, null];
        }

        $prefix = tempnam(sys_get_temp_dir(), 'pdf2img_');
        if ($prefix === false) {
            Log::warning('ExtractAction: tempnam failed; sending raw PDF');

            return [base64_encode((string) file_get_contents($filePath)), $originalMime, null];
        }
        @unlink($prefix);

        $cmd = sprintf(
            'pdftoppm -png -r 150 -f 1 -l 1 %s %s 2>&1',
            escapeshellarg($filePath),
            escapeshellarg($prefix),
        );
        exec($cmd, $output, $rc);

        $generated = $prefix.'-1.png';
        if ($rc !== 0 || ! file_exists($generated)) {
            Log::warning('ExtractAction: pdftoppm failed; sending raw PDF', [
                'cmd' => $cmd,
                'rc' => $rc,
                'output' => $output,
            ]);

            return [base64_encode((string) file_get_contents($filePath)), $originalMime, null];
        }

        return [base64_encode((string) file_get_contents($generated)), 'image/png', $generated];
    }
}
