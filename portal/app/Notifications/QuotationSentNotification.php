<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Document;
use App\Models\QuotationVersion;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class QuotationSentNotification extends Notification
{
    use Queueable;

    public function __construct(
        public readonly QuotationVersion $version,
        public readonly ?Document $document = null,
    ) {}

    /** @return list<string> */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject('Your Quotation from Maruti Travels — v'.$this->version->version_number)
            ->greeting('Hello!')
            ->line('Please find your travel quotation attached.')
            ->line('Quotation Total: ₹'.$this->version->grand_total->toDecimalString())
            ->action('View Your Account', url('/account'))
            ->salutation('Warm regards, Maruti Travels Team');

        if ($this->document !== null) {
            $filePath = storage_path('app/private/'.$this->document->pdf_path);
            if (file_exists($filePath)) {
                $message->attach($filePath, ['as' => 'quotation.pdf', 'mime' => 'application/pdf']);
            }
        }

        return $message;
    }

    /** @return array<string, mixed> */
    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'quotation_sent',
            'quotation_version_id' => $this->version->id,
            'grand_total' => $this->version->grand_total->toDecimalString(),
        ];
    }
}
