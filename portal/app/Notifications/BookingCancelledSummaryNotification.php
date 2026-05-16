<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\ChangeRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingCancelledSummaryNotification extends Notification
{
    use Queueable;

    public function __construct(public readonly ChangeRequest $changeRequest) {}

    /** @return list<string> */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $booking = $this->changeRequest->booking;

        return (new MailMessage)
            ->subject('Booking Cancellation Confirmed — '.$booking->booking_ref)
            ->greeting('Hello!')
            ->line('Your cancellation request for booking '.$booking->booking_ref.' has been processed.')
            ->when(
                $this->changeRequest->customer_facing_summary,
                fn ($msg) => $msg->line($this->changeRequest->customer_facing_summary),
            )
            ->when(
                $this->changeRequest->net_refund_to_customer?->paise > 0,
                fn ($msg) => $msg->line('Refund amount: ₹'.$this->changeRequest->net_refund_to_customer?->toDecimalString()),
            )
            ->action('View Your Account', url('/account'))
            ->salutation('Warm regards, Maruti Travels Team');
    }

    /** @return array<string, mixed> */
    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'booking_cancelled',
            'booking_ref' => $this->changeRequest->booking->booking_ref,
            'change_request_ulid' => $this->changeRequest->ulid,
        ];
    }
}
