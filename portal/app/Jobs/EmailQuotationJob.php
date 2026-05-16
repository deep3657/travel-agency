<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Document;
use App\Models\QuotationVersion;
use App\Notifications\QuotationSentNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class EmailQuotationJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly int $quotationVersionId) {}

    public function handle(): void
    {
        $version = QuotationVersion::with(['quotation.trip.customer.user'])->findOrFail($this->quotationVersionId);
        $customerUser = $version->quotation->trip->customer->user;

        if ($customerUser === null) {
            return;
        }

        $doc = Document::query()
            ->where('quotation_version_id', $version->id)
            ->latest()
            ->first();

        $customerUser->notify(new QuotationSentNotification($version, $doc));
    }
}
