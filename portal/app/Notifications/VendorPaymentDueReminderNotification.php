<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Reminder;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VendorPaymentDueReminderNotification extends Notification
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
        $dueDate = $booking?->vendor_payment_due?->format('d M Y') ?? 'soon';
        $vendorName = $booking !== null && $booking->vendor !== null ? $booking->vendor->name : 'Vendor';

        return (new MailMessage)
            ->subject('Vendor Payment Due — '.$bookingRef)
            ->greeting('Action Required:')
            ->line('Vendor payment for booking '.$bookingRef.' to '.$vendorName.' is due on '.$dueDate.'.')
            ->line('Purchase Cost: ₹'.($booking?->purchase_cost?->toDecimalString() ?? '0.00'))
            ->action('View Booking', url('/admin/bookings'))
            ->salutation('Maruti Travels Admin');
    }

    /** @return array<string, mixed> */
    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'vendor_payment_due',
            'booking_ref' => $this->reminder->booking?->booking_ref,
            'reminder_id' => $this->reminder->id,
        ];
    }
}
