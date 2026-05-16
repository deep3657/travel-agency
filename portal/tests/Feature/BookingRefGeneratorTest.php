<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Support\Booking\BookingRefGenerator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingRefGeneratorTest extends TestCase
{
    use RefreshDatabase;

    public function test_generates_first_ref_for_a_year(): void
    {
        $gen = BookingRefGenerator::fromContainer();

        $ref = $gen->next(2026);

        $this->assertSame('BK-2026-000001', $ref);
    }

    public function test_increments_sequentially(): void
    {
        $gen = BookingRefGenerator::fromContainer();

        $refs = [
            $gen->next(2026),
            $gen->next(2026),
            $gen->next(2026),
        ];

        $this->assertSame(['BK-2026-000001', 'BK-2026-000002', 'BK-2026-000003'], $refs);
    }

    public function test_separates_counters_per_year(): void
    {
        $gen = BookingRefGenerator::fromContainer();

        $a2026 = $gen->next(2026);
        $b2026 = $gen->next(2026);
        $a2027 = $gen->next(2027);
        $c2026 = $gen->next(2026);

        $this->assertSame('BK-2026-000001', $a2026);
        $this->assertSame('BK-2026-000002', $b2026);
        $this->assertSame('BK-2027-000001', $a2027);
        $this->assertSame('BK-2026-000003', $c2026);
    }
}
