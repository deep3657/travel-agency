<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Reminder;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TravelStartReminderNotification extends Notification
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
            ->subject('Your Travel Starts Tomorrow — '.$bookingRef)
            ->greeting('Hello!')
            ->line('Your trip starts tomorrow! Here are a few reminders:')
            ->line('• Keep your booking reference '.$bookingRef.' handy')
            ->line('• Carry all required identification documents')
            ->line('• Arrive at the airport/station at least 2 hours early')
            ->salutation('Have a wonderful journey, Maruti Travels Team');
    }

    /** @return array<string, mixed> */
    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'travel_start_reminder',
            'booking_ref' => $this->reminder->booking?->booking_ref,
            'reminder_id' => $this->reminder->id,
        ];
    }
}
