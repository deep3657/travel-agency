<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Quotation;
use App\Models\Trip;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Quotation>
 */
class QuotationFactory extends Factory
{
    protected $model = Quotation::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'trip_id' => Trip::factory(),
            'validity_date' => $this->faker->dateTimeBetween('+1 week', '+1 month')->format('Y-m-d'),
            'status' => $this->faker->randomElement(['draft', 'sent', 'accepted']),
        ];
    }
}
