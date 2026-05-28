<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\AgencySetting;
use Illuminate\Database\Seeder;

/**
 * Idempotently writes the singleton agency_settings row (LLD §18).
 * Real GSTIN, state, and CA-confirmed GST rates land via M16 owner-input
 * (PRD §10 — pending-owner items).
 */
class AgencySettingsSeeder extends Seeder
{
    public function run(): void
    {
        AgencySetting::query()->updateOrCreate(
            ['id' => AgencySetting::SINGLETON_ID],
            [
                'agency_name' => 'Maruti Travels',
                'agency_legal_name' => 'Maruti Travels',
                'gstin' => null,
                'pan' => null,
                'state' => 'West Bengal',
                'address' => 'Kolkata, West Bengal',
                'city' => 'Kolkata',
                'pincode' => null,
                'phone' => '+91 93310 27837',
                'email' => 'marutitravelscc@gmail.com',
                'website' => null,

                'gst_rate_package' => 5.00,
                'gst_rate_flight_service' => 18.00,
                'gst_rate_hotel_service' => 18.00,
                'gst_rate_other' => 18.00,

                'ai_provider_primary' => 'gemini',
                'ai_provider_fallback' => 'openai',
                'ai_model_primary' => 'gemini-2.0-flash',
                'ai_model_fallback' => 'gpt-4o-mini',
                'ai_monthly_cap_inr' => 300.00,
                'ai_confidence_threshold' => 0.70,

                'reminder_lead_web_checkin_hours' => 48,
                'reminder_lead_travel_start_hours' => 24,
                'reminder_lead_reconfirmation_days' => 7,
                'reminder_lead_payment_due_days_csv' => '7,3,1',

                'branding_logo_path' => null,
                'branding_accent_hex' => '#0F4C81',
                'default_terms_quotation' => 'Terms pending finalisation by Maruti Travels.',
                'default_terms_voucher' => 'Terms pending finalisation by Maruti Travels.',
            ],
        );
    }
}
