<?php

declare(strict_types=1);

namespace App\Support\Booking;

use App\Exceptions\BookingRefGenerationException;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * Atomic per-year sequence generator for booking references (LLD §8.7).
 *
 * Backed by `bookings_seq (year SMALLINT PK, last_seq INT)`. The increment
 * happens inside a transaction with row-level lock semantics:
 *   1. UPDATE bookings_seq SET last_seq = last_seq + 1 WHERE year = ?
 *   2. If 0 rows affected, INSERT IGNORE the year row with last_seq = 1
 *      (handles concurrent first-call-of-year safely).
 *   3. SELECT last_seq for the row.
 *
 * Output format: `BK-{YYYY}-{seq6}` zero-padded, e.g. `BK-2026-000123`.
 */
final class BookingRefGenerator
{
    public function __construct(
        private readonly ConnectionInterface $db,
    ) {}

    public function next(?int $year = null): string
    {
        $year ??= (int) date('Y');

        try {
            $seq = $this->db->transaction(function () use ($year): int {
                $affected = $this->db->update(
                    'UPDATE bookings_seq SET last_seq = last_seq + 1 WHERE year = ?',
                    [$year],
                );

                if ($affected === 0) {
                    // First booking of the year — race-safe insert.
                    // `insertOrIgnore` translates to MySQL `INSERT IGNORE` and
                    // SQLite `INSERT OR IGNORE`, so the same code works in
                    // both production and tests. If we lose the race the row
                    // exists with last_seq=1 already; if we won, also 1.
                    // Either way, the SELECT below reads the correct value.
                    $this->db->table('bookings_seq')->insertOrIgnore([
                        'year' => $year,
                        'last_seq' => 1,
                    ]);
                }

                $row = $this->db->selectOne(
                    'SELECT last_seq FROM bookings_seq WHERE year = ? LIMIT 1',
                    [$year],
                );

                if ($row === null) {
                    throw new BookingRefGenerationException(
                        "bookings_seq row for year {$year} unexpectedly missing after upsert.",
                    );
                }

                return (int) ($row->last_seq ?? $row['last_seq'] ?? 0);
            });
        } catch (BookingRefGenerationException $e) {
            throw $e;
        } catch (Throwable $e) {
            throw new BookingRefGenerationException(
                'Failed to allocate booking reference: '.$e->getMessage(),
                previous: $e,
            );
        }

        return sprintf('BK-%04d-%06d', $year, $seq);
    }

    public static function fromContainer(): self
    {
        return new self(DB::connection());
    }
}
