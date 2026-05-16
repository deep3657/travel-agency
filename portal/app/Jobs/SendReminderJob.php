<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Reminder;
use App\Notifications\CustomerPaymentDueReminderNotification;
use App\Notifications\ReconfirmationReminderNotification;
use App\Notifications\TravelStartReminderNotification;
use App\Notifications\VendorPaymentDueReminderNotification;
use App\Notifications\WebCheckinReminderNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendReminderJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly int $reminderId) {}

    public function handle(): void
    {
        $reminder = Reminder::with(['recipient', 'booking'])->findOrFail($this->reminderId);

        if ($reminder->fired_at !== null) {
            return;
        }

        $recipient = $reminder->recipient;
        if ($recipient === null) {
            return;
        }

        $notification = match ($reminder->reminder_type) {
            'web_checkin' => new WebCheckinReminderNotification($reminder),
            'travel_start' => new TravelStartReminderNotification($reminder),
            'reconfirmation' => new ReconfirmationReminderNotification($reminder),
            'customer_payment_due' => new CustomerPaymentDueReminderNotification($reminder),
            'vendor_payment_due' => new VendorPaymentDueReminderNotification($reminder),
            default => null,
        };

        if ($notification !== null) {
            $recipient->notify($notification);
        }

        $reminder->update(['fired_at' => now()]);
    }
}
