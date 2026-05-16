<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->char('ulid', 26)->unique();
            $table->string('booking_ref', 20)->unique();
            $table->foreignId('trip_id')->constrained('trips')->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->string('booking_type', 16)->default('package');
            $table->string('agency_pnr', 40)->nullable();
            $table->foreignId('vendor_id')->nullable()->constrained('vendors')->nullOnDelete();
            $table->string('vendor_pnr', 40)->nullable();
            $table->decimal('sale_amount', 12, 2)->default(0);
            $table->decimal('purchase_cost', 12, 2)->nullable();
            $table->string('payment_status', 20)->default('unpaid');
            $table->date('customer_payment_due')->nullable();
            $table->date('vendor_payment_due')->nullable();
            $table->string('status', 20)->default('pending_confirmation');
            $table->json('flight_data')->nullable();
            $table->json('hotel_data')->nullable();
            $table->json('package_data')->nullable();
            $table->text('notes')->nullable();

            $table->foreignId('created_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by_id')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();

            $table->index('customer_id');
            $table->index('trip_id');
            $table->index('status');
            $table->index('payment_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
