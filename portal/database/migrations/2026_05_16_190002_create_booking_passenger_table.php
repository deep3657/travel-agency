<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_passenger', function (Blueprint $table) {
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->foreignId('passenger_id')->constrained('passengers')->cascadeOnDelete();
            $table->boolean('is_lead')->default(false);

            $table->primary(['booking_id', 'passenger_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_passenger');
    }
};
