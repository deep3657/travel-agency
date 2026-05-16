<?php

declare(strict_types=1);

namespace Tests\Unit\Support;

use App\Support\Money\MoneyVo;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class MoneyVoTest extends TestCase
{
    public function test_constructs_from_rupees_int(): void
    {
        $m = MoneyVo::rupees(100);
        $this->assertSame(10000, $m->paise);
        $this->assertSame('100.00', $m->toDecimalString());
    }

    public function test_constructs_from_rupees_float(): void
    {
        $m = MoneyVo::rupees(1234.56);
        $this->assertSame(123456, $m->paise);
        $this->assertSame('1234.56', $m->toDecimalString());
    }

    public function test_constructs_from_decimal_string(): void
    {
        $m = MoneyVo::rupees('99.99');
        $this->assertSame(9999, $m->paise);
    }

    public function test_addition_is_lossless(): void
    {
        // The classic 0.1 + 0.2 != 0.3 problem must not show up.
        $sum = MoneyVo::rupees(0.10)->plus(MoneyVo::rupees(0.20));
        $this->assertSame('0.30', $sum->toDecimalString());
    }

    public function test_subtraction(): void
    {
        $r = MoneyVo::rupees(500.00)->minus(MoneyVo::rupees(199.95));
        $this->assertSame('300.05', $r->toDecimalString());
    }

    public function test_times(): void
    {
        $r = MoneyVo::rupees(100.00)->times(0.18);
        $this->assertSame('18.00', $r->toDecimalString());
    }

    public function test_zero(): void
    {
        $this->assertTrue(MoneyVo::zero()->isZero());
        $this->assertFalse(MoneyVo::zero()->isNegative());
    }

    public function test_rejects_non_numeric_string(): void
    {
        $this->expectException(InvalidArgumentException::class);
        MoneyVo::rupees('not-a-number');
    }

    public function test_overflow_guard(): void
    {
        $this->expectException(InvalidArgumentException::class);
        MoneyVo::paise(1_000_000_000_000); // > DECIMAL(12,2) max
    }

    public function test_equality(): void
    {
        $a = MoneyVo::rupees(42);
        $b = MoneyVo::rupees(42.00);
        $this->assertTrue($a->equals($b));
    }
}
