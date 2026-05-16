<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reminders', function (Blueprint $table) {
            $table->id();
            $table->char('ulid', 26)->unique();
            $table->foreignId('booking_id')->nullable()->constrained('bookings')->nullOnDelete();
            $table->foreignId('change_request_id')->nullable()->constrained('change_requests')->nullOnDelete();
            $table->string('reminder_type', 40);
            $table->timestamp('trigger_at');
            $table->timestamp('fired_at')->nullable();
            $table->string('dedup_key', 120)->unique();
            $table->foreignId('recipient_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->json('payload')->nullable();
            $table->timestamps();

            $table->index('trigger_at');
            $table->index('fired_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reminders');
    }
};
