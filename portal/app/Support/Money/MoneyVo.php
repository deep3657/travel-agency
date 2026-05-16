<?php

declare(strict_types=1);

namespace App\Support\Money;

use InvalidArgumentException;
use Stringable;

/**
 * Immutable money value object — INR-only for v1 (LLD §2.2).
 *
 * Stored internally as integer paise to avoid float drift across arithmetic.
 * Public API works with rupees (DECIMAL(12,2) on disk) but conversion is
 * always lossless because we restrict construction to two-decimal inputs.
 */
final class MoneyVo implements Stringable
{
    public function __construct(public readonly int $paise)
    {
        if ($paise < -999_999_999_999 || $paise > 999_999_999_999) {
            throw new InvalidArgumentException('MoneyVo overflow — value exceeds DECIMAL(12,2).');
        }
    }

    public static function rupees(int|float|string $rupees): self
    {
        if (is_string($rupees) && ! is_numeric($rupees)) {
            throw new InvalidArgumentException("MoneyVo: non-numeric input '{$rupees}'.");
        }

        // Multiply with intentional 2-decimal rounding; PHP float math is fine
        // here because input is bounded and we round before integer cast.
        $paise = (int) round(((float) $rupees) * 100, 0);

        return new self($paise);
    }

    public static function paise(int $paise): self
    {
        return new self($paise);
    }

    public static function zero(): self
    {
        return new self(0);
    }

    public function toRupees(): float
    {
        return $this->paise / 100;
    }

    public function plus(self $other): self
    {
        return new self($this->paise + $other->paise);
    }

    public function minus(self $other): self
    {
        return new self($this->paise - $other->paise);
    }

    public function times(int|float $factor): self
    {
        return new self((int) round($this->paise * $factor));
    }

    public function isZero(): bool
    {
        return $this->paise === 0;
    }

    public function isNegative(): bool
    {
        return $this->paise < 0;
    }

    public function equals(self $other): bool
    {
        return $this->paise === $other->paise;
    }

    /**
     * Format as a fixed-2-decimal string suitable for DECIMAL(12,2) storage.
     */
    public function toDecimalString(): string
    {
        return number_format($this->toRupees(), 2, '.', '');
    }

    public function __toString(): string
    {
        return $this->toDecimalString();
    }
}
