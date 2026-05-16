<?php

declare(strict_types=1);

namespace App\Casts;

use App\Support\Money\MoneyVo;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

/**
 * Cast a DECIMAL(12,2) column to App\Support\Money\MoneyVo (LLD §2.2).
 *
 * @implements CastsAttributes<MoneyVo, MoneyVo|int|float|string>
 */
final class MoneyCast implements CastsAttributes
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): ?MoneyVo
    {
        if ($value === null) {
            return null;
        }

        return MoneyVo::rupees($value);
    }

    /**
     * @param  array<string, mixed>  $attributes
     * @return array<string, string|null>
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): array
    {
        if ($value === null) {
            return [$key => null];
        }

        if ($value instanceof MoneyVo) {
            return [$key => $value->toDecimalString()];
        }

        if (is_int($value) || is_float($value) || is_numeric($value)) {
            return [$key => MoneyVo::rupees($value)->toDecimalString()];
        }

        throw new InvalidArgumentException(
            'MoneyCast: cannot serialise value of type '.get_debug_type($value)." for column '{$key}'.",
        );
    }
}
