<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\QuotationLine;
use App\Models\QuotationVersion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<QuotationLine>
 */
class QuotationLineFactory extends Factory
{
    protected $model = QuotationLine::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        $quantity = $this->faker->numberBetween(1, 4);
        $unitRate = $this->faker->randomFloat(2, 2000, 20000);

        return [
            'quotation_version_id' => QuotationVersion::factory(),
            'line_type' => $this->faker->randomElement(['flight', 'hotel', 'package', 'other']),
            'description' => $this->faker->sentence(5),
            'quantity' => $quantity,
            'unit_rate' => $unitRate,
            'amount' => $quantity * $unitRate,
        ];
    }
}
