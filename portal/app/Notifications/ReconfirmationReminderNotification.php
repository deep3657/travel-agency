<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Reminder;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReconfirmationReminderNotification extends Notification
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
        $bookingRef = ($this->reminder->booking !== null ? $this->reminder->booking->booking_ref : 'N/A');

        return (new MailMessage)
            ->subject('Booking Reconfirmation Required — '.$bookingRef)
            ->greeting('Hello!')
            ->line('Please reconfirm your booking '.$bookingRef.' with the vendor at least 7 days before travel.')
            ->action('View Booking', url('/account/trips'))
            ->salutation('Warm regards, Maruti Travels Team');
    }

    /** @return array<string, mixed> */
    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'reconfirmation_reminder',
            'booking_ref' => $this->reminder->booking?->booking_ref,
            'reminder_id' => $this->reminder->id,
        ];
    }
}
