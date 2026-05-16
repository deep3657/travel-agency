<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Trip;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Trip>
 */
class TripFactory extends Factory
{
    protected $model = Trip::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('+1 month', '+6 months');

        return [
            'customer_id' => Customer::factory(),
            'name' => $this->faker->randomElement([
                'Family Vacation',
                'Honeymoon Trip',
                'Corporate Retreat',
                'Anniversary Celebration',
                'Adventure Holiday',
            ]).' '.$this->faker->year(),
            'primary_destination' => $this->faker->randomElement(['Goa', 'Kerala', 'Rajasthan', 'Himachal', 'Andaman']),
            'travel_start' => $start->format('Y-m-d'),
            'travel_end' => $this->faker->dateTimeBetween($start, '+9 months')->format('Y-m-d'),
            'status' => $this->faker->randomElement(['planning', 'confirmed', 'completed']),
        ];
    }
}
