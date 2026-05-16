<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Enquiry;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Enquiry>
 */
class EnquiryFactory extends Factory
{
    protected $model = Enquiry::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'enquiry_type' => $this->faker->randomElement(['flight', 'hotel', 'package', 'mixed']),
            'travel_from' => $this->faker->dateTimeBetween('+1 month', '+6 months')->format('Y-m-d'),
            'travel_to' => $this->faker->dateTimeBetween('+6 months', '+9 months')->format('Y-m-d'),
            'origin' => $this->faker->randomElement(['Mumbai', 'Delhi', 'Bangalore', 'Chennai']),
            'destination' => $this->faker->randomElement(['Goa', 'Kerala', 'Rajasthan', 'Himachal', 'Ladakh']),
            'pax_adult' => $this->faker->numberBetween(1, 6),
            'pax_child' => $this->faker->numberBetween(0, 2),
            'pax_infant' => 0,
            'budget_min' => $this->faker->randomFloat(2, 20000, 50000),
            'budget_max' => $this->faker->randomFloat(2, 50000, 200000),
            'status' => $this->faker->randomElement(['new', 'in_progress', 'quoted', 'closed']),
            'created_via' => $this->faker->randomElement(['admin_entry', 'customer_portal']),
        ];
    }
}
