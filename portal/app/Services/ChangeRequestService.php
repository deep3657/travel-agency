<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Booking;
use App\Models\ChangeRequest;
use App\Models\User;
use App\Notifications\BookingCancelledSummaryNotification;
use Illuminate\Support\Str;

final class ChangeRequestService
{
    /** @param array<string, mixed> $data */
    public function open(Booking $b, array $data, User $actor): ChangeRequest
    {
        $data['ulid'] ??= (string) Str::ulid();
        $data['booking_id'] = $b->id;

        $cr = ChangeRequest::query()->create($data);

        $net = $cr->computeNetRefund();
        $cr->update(['net_refund_to_customer' => $net]);

        return $cr->refresh();
    }

    /** @param array<string, mixed> $data */
    public function update(ChangeRequest $cr, array $data, User $actor): void
    {
        $cr->update($data);

        $net = $cr->refresh()->computeNetRefund();
        $cr->update(['net_refund_to_customer' => $net]);
    }

    public function complete(ChangeRequest $cr, User $actor): void
    {
        $cr->update(['status' => 'completed']);

        $booking = $cr->booking;
        if ($cr->request_type === 'cancellation') {
            $booking->update(['status' => 'cancelled']);
        }

        $customer = $booking->customer;
        $customerUser = $customer->user;
        if ($customerUser) {
            $customerUser->notify(new BookingCancelledSummaryNotification($cr));
        }
    }
}
