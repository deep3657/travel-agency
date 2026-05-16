<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\SendReminderJob;
use App\Models\AgencySetting;
use App\Models\Booking;
use App\Models\Reminder;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ScanRemindersCommand extends Command
{
    protected $signature = 'reminders:scan';

    protected $description = 'Scan active bookings and create/fire reminder notifications';

    public function handle(): int
    {
        $settings = AgencySetting::singleton();

        $webCheckinHours = (int) $settings->reminder_lead_web_checkin_hours;
        $travelStartHours = (int) $settings->reminder_lead_travel_start_hours;
        $reconfirmationDays = (int) $settings->reminder_lead_reconfirmation_days;
        $paymentDaysCsv = $settings->reminder_lead_payment_due_days_csv ?? '7,3,1';
        $paymentDays = array_map('intval', explode(',', $paymentDaysCsv));

        $bookings = Booking::query()
            ->with(['trip', 'customer.user'])
            ->whereNotIn('status', ['cancelled', 'completed'])
            ->get();

        $created = 0;

        foreach ($bookings as $booking) {
            $recipientUserId = $booking->customer->user?->id;

            // Web check-in reminder
            if ($booking->booking_type === 'flight' && $booking->trip->travel_start !== null) {
                $triggerAt = Carbon::instance($booking->trip->travel_start)->subHours($webCheckinHours);
                $dedupKey = "web_checkin_{$booking->id}_{$booking->trip->travel_start}";
                $created += $this->insertReminder('web_checkin', $triggerAt, $dedupKey, $booking->id, $recipientUserId);
            }

            // Travel start reminder
            if ($booking->trip->travel_start !== null) {
                $triggerAt = Carbon::instance($booking->trip->travel_start)->subHours($travelStartHours);
                $dedupKey = "travel_start_{$booking->id}_{$booking->trip->travel_start}";
                $created += $this->insertReminder('travel_start', $triggerAt, $dedupKey, $booking->id, $recipientUserId);
            }

            // Reconfirmation reminder
            if ($booking->trip->travel_start !== null) {
                $triggerAt = Carbon::instance($booking->trip->travel_start)->subDays($reconfirmationDays);
                $dedupKey = "reconfirmation_{$booking->id}_{$booking->trip->travel_start}";
                $created += $this->insertReminder('reconfirmation', $triggerAt, $dedupKey, $booking->id, $recipientUserId);
            }

            // Customer payment due reminders
            if ($booking->customer_payment_due !== null) {
                foreach ($paymentDays as $daysBefore) {
                    $triggerAt = Carbon::instance($booking->customer_payment_due)->subDays($daysBefore);
                    $dedupKey = "customer_payment_{$booking->id}_{$daysBefore}d";
                    $created += $this->insertReminder('customer_payment_due', $triggerAt, $dedupKey, $booking->id, $recipientUserId);
                }
            }

            // Vendor payment due reminders (send to admin users)
            if ($booking->vendor_payment_due !== null) {
                foreach ($paymentDays as $daysBefore) {
                    $triggerAt = Carbon::instance($booking->vendor_payment_due)->subDays($daysBefore);
                    $dedupKey = "vendor_payment_{$booking->id}_{$daysBefore}d";
                    $adminId = $booking->creator?->id;
                    $created += $this->insertReminder('vendor_payment_due', $triggerAt, $dedupKey, $booking->id, $adminId);
                }
            }
        }

        // Dispatch jobs for newly due reminders
        $due = Reminder::query()
            ->whereNull('fired_at')
            ->where('trigger_at', '<=', now())
            ->get();

        foreach ($due as $reminder) {
            SendReminderJob::dispatch($reminder->id);
        }

        $this->info("Created {$created} new reminders. Dispatched {$due->count()} due reminders.");

        return Command::SUCCESS;
    }

    private function insertReminder(
        string $type,
        Carbon $triggerAt,
        string $dedupKey,
        int $bookingId,
        ?int $recipientUserId,
    ): int {
        if ($recipientUserId === null) {
            return 0;
        }

        $inserted = DB::table('reminders')->insertOrIgnore([[
            'ulid' => (string) Str::ulid(),
            'booking_id' => $bookingId,
            'reminder_type' => $type,
            'trigger_at' => $triggerAt->toDateTimeString(),
            'dedup_key' => $dedupKey,
            'recipient_user_id' => $recipientUserId,
            'created_at' => now()->toDateTimeString(),
            'updated_at' => now()->toDateTimeString(),
        ]]);

        return $inserted;
    }
}
