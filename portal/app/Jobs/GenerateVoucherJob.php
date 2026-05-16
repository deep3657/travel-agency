<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Booking;
use App\Models\User;
use App\Services\VoucherPdfService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class GenerateVoucherJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly int $bookingId,
        public readonly int $actorId,
    ) {}

    public function handle(VoucherPdfService $service): void
    {
        $booking = Booking::findOrFail($this->bookingId);
        $actor = User::findOrFail($this->actorId);
        $service->generate($booking, $actor);
    }
}
