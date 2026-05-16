<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Booking;
use App\Models\Document;
use App\Models\User;
use App\Notifications\VoucherIssuedNotification;

final class VoucherService
{
    public function __construct(private readonly VoucherPdfService $pdfService) {}

    public function generate(Booking $b, User $actor): Document
    {
        return $this->pdfService->generate($b, $actor);
    }

    public function emailToCustomer(Document $doc, User $actor): void
    {
        $booking = $doc->booking;
        if ($booking === null) {
            return;
        }

        $customerUser = $booking->customer->user;
        if ($customerUser) {
            $customerUser->notify(new VoucherIssuedNotification($doc, $booking));
        }
    }
}
