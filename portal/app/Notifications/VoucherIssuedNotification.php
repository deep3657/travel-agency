<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Booking;
use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VoucherIssuedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public readonly Document $document,
        public readonly Booking $booking,
    ) {}

    /** @return list<string> */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject('Your Travel Voucher — Maruti Travels')
            ->greeting('Hello!')
            ->line('Your travel voucher for booking '.$this->booking->booking_ref.' is ready.')
            ->action('View Your Trips', url('/account/trips'))
            ->salutation('Warm regards, Maruti Travels Team');

        $filePath = storage_path('app/private/'.$this->document->pdf_path);
        if (file_exists($filePath)) {
            $message->attach($filePath, ['as' => 'voucher.pdf', 'mime' => 'application/pdf']);
        }

        return $message;
    }

    /** @return array<string, mixed> */
    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'voucher_issued',
            'booking_ref' => $this->booking->booking_ref,
            'document_ulid' => $this->document->ulid,
        ];
    }
}
