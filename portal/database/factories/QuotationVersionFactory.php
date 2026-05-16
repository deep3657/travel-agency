<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Quotation;
use App\Models\QuotationVersion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<QuotationVersion>
 */
class QuotationVersionFactory extends Factory
{
    protected $model = QuotationVersion::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        $subtotal = $this->faker->randomFloat(2, 10000, 100000);
        $discount = (float) ($this->faker->optional(0.3, 0)->randomFloat(2, 0, $subtotal * 0.1));
        $taxable = $subtotal - $discount;
        $cgst = $taxable * 0.025;
        $sgst = $taxable * 0.025;
        $grandTotal = $taxable + $cgst + $sgst;

        return [
            'quotation_id' => Quotation::factory(),
            'version_number' => 1,
            'subtotal' => $subtotal,
            'discount_amount' => $discount,
            'cgst' => $cgst,
            'sgst' => $sgst,
            'igst' => 0,
            'grand_total' => $grandTotal,
        ];
    }
}
