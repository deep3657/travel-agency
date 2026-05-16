<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Document;
use App\Models\User;
use App\Services\VoucherService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class EmailVoucherJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly int $documentId,
        public readonly int $actorId,
    ) {}

    public function handle(VoucherService $service): void
    {
        $doc = Document::findOrFail($this->documentId);
        $actor = User::findOrFail($this->actorId);
        $service->emailToCustomer($doc, $actor);
    }
}
