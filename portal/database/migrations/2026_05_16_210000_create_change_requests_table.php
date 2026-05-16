<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('change_requests', function (Blueprint $table) {
            $table->id();
            $table->char('ulid', 26)->unique();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->string('request_type', 20);
            $table->string('requested_by', 16)->default('customer');
            $table->foreignId('requested_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('reason')->nullable();
            $table->string('status', 20)->default('open');
            $table->foreignId('assigned_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('vendor_fee', 12, 2)->nullable();
            $table->decimal('refund_from_vendor', 12, 2)->nullable();
            $table->decimal('agency_service_fee', 12, 2)->nullable();
            $table->decimal('net_refund_to_customer', 12, 2)->nullable();
            $table->string('refund_mode', 20)->nullable();
            $table->timestamp('refund_settled_at')->nullable();
            $table->text('customer_facing_summary')->nullable();

            $table->foreignId('created_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by_id')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();

            $table->index('booking_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('change_requests');
    }
};
