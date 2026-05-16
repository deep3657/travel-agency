<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Single-row settings table — column-per-setting (HLD §7.2, LLD §3.11).
     * Singleton enforced in app code via AgencySetting::singleton(); seeder
     * inserts the lone row.
     */
    public function up(): void
    {
        Schema::create('agency_settings', function (Blueprint $table) {
            $table->id();

            // Identity / GST registration
            $table->string('agency_name', 190)->default('Maruti Travels');
            $table->string('agency_legal_name', 190)->nullable();
            $table->string('gstin', 15)->nullable();
            $table->string('pan', 10)->nullable();
            $table->string('state', 80)->nullable();
            $table->string('address', 500)->nullable();
            $table->string('city', 80)->nullable();
            $table->string('pincode', 10)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email', 190)->nullable();
            $table->string('website', 190)->nullable();

            // GST rates per service type (DECIMAL(5,2)) — defaults until CA confirmation (PRD §10).
            $table->decimal('gst_rate_package', 5, 2)->default(5.00);
            $table->decimal('gst_rate_flight_service', 5, 2)->default(18.00);
            $table->decimal('gst_rate_hotel_service', 5, 2)->default(18.00);
            $table->decimal('gst_rate_other', 5, 2)->default(18.00);

            // AI extraction config
            $table->string('ai_provider_primary', 32)->default('gemini');
            $table->string('ai_provider_fallback', 32)->default('openai');
            $table->string('ai_model_primary', 64)->default('gemini-2.0-flash');
            $table->string('ai_model_fallback', 64)->default('gpt-4o-mini');
            $table->decimal('ai_monthly_cap_inr', 12, 2)->default(300.00);
            $table->decimal('ai_confidence_threshold', 4, 2)->default(0.70);

            // Reminder lead times
            $table->unsignedSmallInteger('reminder_lead_web_checkin_hours')->default(48);
            $table->unsignedSmallInteger('reminder_lead_travel_start_hours')->default(24);
            $table->unsignedSmallInteger('reminder_lead_reconfirmation_days')->default(7);
            $table->string('reminder_lead_payment_due_days_csv', 60)->default('7,3,1');

            // Branding
            $table->string('branding_logo_path', 255)->nullable();
            $table->string('branding_accent_hex', 9)->default('#0F4C81');
            $table->text('default_terms_quotation')->nullable();
            $table->text('default_terms_voucher')->nullable();

            $table->foreignId('updated_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agency_settings');
    }
};
