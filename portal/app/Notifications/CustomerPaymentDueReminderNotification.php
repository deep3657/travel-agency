<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Reminder;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomerPaymentDueReminderNotification extends Notification
{
    use Queueable;

    public function __construct(public readonly Reminder $reminder) {}

    /** @return list<string> */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $booking = $this->reminder->booking;
        $bookingRef = $booking !== null ? $booking->booking_ref : 'N/A';
        $dueDate = $booking?->customer_payment_due?->format('d M Y') ?? 'soon';

        return (new MailMessage)
            ->subject('Payment Due Reminder — '.$bookingRef)
            ->greeting('Hello!')
            ->line('Your payment for booking '.$bookingRef.' is due on '.$dueDate.'.')
            ->line('Amount: ₹'.($booking?->sale_amount?->toDecimalString() ?? '0.00'))
            ->action('View Account', url('/account'))
            ->salutation('Warm regards, Maruti Travels Team');
    }

    /** @return array<string, mixed> */
    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'customer_payment_due',
            'booking_ref' => $this->reminder->booking?->booking_ref,
            'reminder_id' => $this->reminder->id,
        ];
    }
}
