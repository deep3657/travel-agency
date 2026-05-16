<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\DB;

/**
 * Generates unique booking references in format MT-YYYY-NNNN.
 * Uses the bookings_seq table for atomic sequential numbering.
 */
final class BookingRefGenerator
{
    public function next(): string
    {
        $year = (int) date('Y');

        DB::table('bookings_seq')
            ->insertOrIgnore(['year' => $year, 'last_seq' => 0]);

        $seq = DB::table('bookings_seq')
            ->where('year', $year)
            ->lockForUpdate()
            ->value('last_seq');

        $next = (int) $seq + 1;

        DB::table('bookings_seq')
            ->where('year', $year)
            ->update(['last_seq' => $next]);

        return sprintf('MT-%d-%04d', $year, $next);
    }
}
