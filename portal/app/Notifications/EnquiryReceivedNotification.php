<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Enquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EnquiryReceivedNotification extends Notification
{
    use Queueable;

    public function __construct(public readonly Enquiry $enquiry) {}

    /** @return list<string> */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('We received your enquiry — Maruti Travels')
            ->greeting('Hello!')
            ->line('Thank you for reaching out to Maruti Travels.')
            ->line('We have received your enquiry for '.($this->enquiry->destination ?? 'your trip').' and our team will get back to you shortly.')
            ->line('Enquiry Reference: '.$this->enquiry->ulid)
            ->action('View Your Account', url('/account/enquiries'))
            ->salutation('Warm regards, Maruti Travels Team');
    }

    /** @return array<string, mixed> */
    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'enquiry_received',
            'enquiry_ulid' => $this->enquiry->ulid,
            'destination' => $this->enquiry->destination,
        ];
    }
}
