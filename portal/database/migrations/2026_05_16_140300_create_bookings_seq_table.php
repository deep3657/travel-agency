<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Per-year monotonically-increasing counter that backs BookingRefGenerator
     * (LLD §8.7). Row-level lock via UPDATE ... WHERE year = ? guarantees
     * atomic increment under concurrent inserts without resorting to LOCK TABLES.
     */
    public function up(): void
    {
        Schema::create('bookings_seq', function (Blueprint $table) {
            $table->unsignedSmallInteger('year')->primary();
            $table->unsignedInteger('last_seq')->default(0);
            // `updated_at` is informational. `useCurrentOnUpdate()` is
            // MySQL-only; sqlite (used in tests) silently ignores it. We
            // accept the divergence because this column never drives logic.
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings_seq');
    }
};
