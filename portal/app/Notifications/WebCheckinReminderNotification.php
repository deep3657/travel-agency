<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Reminder;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WebCheckinReminderNotification extends Notification
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
            ->subject('Web Check-in Reminder — '.$bookingRef)
            ->greeting('Hello!')
            ->line('This is a reminder to complete your web check-in for booking '.$bookingRef.'.')
            ->line('Web check-in is typically available 48 hours before departure.')
            ->salutation('Safe travels, Maruti Travels Team');
    }

    /** @return array<string, mixed> */
    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'web_checkin_reminder',
            'booking_ref' => $this->reminder->booking?->booking_ref,
            'reminder_id' => $this->reminder->id,
        ];
    }
}
