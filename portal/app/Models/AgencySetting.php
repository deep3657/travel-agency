<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RuntimeException;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Singleton settings row for Maruti Travels (LLD §3.11, §4.4).
 *
 * The table physically allows multiple rows but the application enforces a
 * singleton via {@see static::singleton()} which always returns id=1. A new
 * row is auto-created on first access if the seeder hasn't run.
 */
class AgencySetting extends Model
{
    use LogsActivity;

    public const SINGLETON_ID = 1;

    /** @var list<string> */
    protected $fillable = [
        'agency_name',
        'agency_legal_name',
        'gstin',
        'pan',
        'state',
        'address',
        'city',
        'pincode',
        'phone',
        'email',
        'website',
        'gst_rate_package',
        'gst_rate_flight_service',
        'gst_rate_hotel_service',
        'gst_rate_other',
        'ai_provider_primary',
        'ai_provider_fallback',
        'ai_model_primary',
        'ai_model_fallback',
        'ai_monthly_cap_inr',
        'ai_confidence_threshold',
        'reminder_lead_web_checkin_hours',
        'reminder_lead_travel_start_hours',
        'reminder_lead_reconfirmation_days',
        'reminder_lead_payment_due_days_csv',
        'branding_logo_path',
        'branding_accent_hex',
        'default_terms_quotation',
        'default_terms_voucher',
        'updated_by_id',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'gst_rate_package' => 'decimal:2',
            'gst_rate_flight_service' => 'decimal:2',
            'gst_rate_hotel_service' => 'decimal:2',
            'gst_rate_other' => 'decimal:2',
            'ai_monthly_cap_inr' => 'decimal:2',
            'ai_confidence_threshold' => 'decimal:2',
        ];
    }

    public static function singleton(): self
    {
        // First call after a fresh install triggers row creation. Subsequent
        // calls hit the indexed PK lookup. Cache invalidation lives in
        // SettingsForm::save() which calls forgetCache().
        $instance = static::find(self::SINGLETON_ID);

        if ($instance === null) {
            $instance = static::query()->create([
                'agency_name' => 'Maruti Travels',
            ]);

            // If somehow not id=1 (e.g. rows were inserted manually) we surface
            // the misconfiguration loudly rather than silently mismatching.
            if ($instance->id !== self::SINGLETON_ID) {
                throw new RuntimeException(
                    'AgencySetting singleton expected id='.self::SINGLETON_ID
                    .' but got id='.$instance->id.'. The agency_settings table'
                    .' must contain exactly one row.',
                );
            }
        }

        return $instance;
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function editor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'agency_name', 'agency_legal_name', 'gstin', 'pan', 'state',
                'address', 'city', 'pincode', 'phone', 'email', 'website',
                'gst_rate_package', 'gst_rate_flight_service', 'gst_rate_hotel_service', 'gst_rate_other',
                'ai_provider_primary', 'ai_provider_fallback', 'ai_model_primary', 'ai_model_fallback',
                'ai_monthly_cap_inr', 'ai_confidence_threshold',
                'reminder_lead_web_checkin_hours', 'reminder_lead_travel_start_hours',
                'reminder_lead_reconfirmation_days', 'reminder_lead_payment_due_days_csv',
                'branding_accent_hex',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
