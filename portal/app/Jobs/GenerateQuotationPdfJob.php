<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\QuotationVersion;
use App\Models\User;
use App\Services\QuotationPdfService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class GenerateQuotationPdfJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly int $quotationVersionId,
        public readonly int $actorId,
    ) {}

    public function handle(QuotationPdfService $service): void
    {
        $version = QuotationVersion::findOrFail($this->quotationVersionId);
        $actor = User::findOrFail($this->actorId);
        $service->generate($version, $actor);
    }
}
