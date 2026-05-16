<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Customer>
 */
class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'phone' => '+91 '.$this->faker->numerify('9#########'),
            'alt_phone' => null,
            'email' => $this->faker->unique()->safeEmail(),
            'address_line1' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'state' => $this->faker->randomElement([
                'Maharashtra', 'Karnataka', 'Tamil Nadu', 'Delhi', 'Gujarat',
                'Rajasthan', 'Uttar Pradesh', 'West Bengal', 'Telangana', 'Kerala',
            ]),
            'pincode' => (string) $this->faker->numerify('######'),
            'country' => 'India',
            'gstin' => null,
            'company_name' => null,
            'pan' => null,
            'notes' => null,
            'tags' => [],
        ];
    }

    /**
     * State for a business/GST customer.
     */
    public function withGstin(): static
    {
        return $this->state(function () {
            $stateCode = $this->faker->numberBetween(10, 37);
            $pan = strtoupper(
                $this->faker->lexify('?????').
                $this->faker->numerify('####').
                $this->faker->lexify('?'),
            );
            $gstin = $stateCode.$pan.$this->faker->numerify('#').'Z'.$this->faker->lexify('?');

            return [
                'gstin' => strtoupper($gstin),
                'company_name' => $this->faker->company(),
                'pan' => $pan,
            ];
        });
    }
}
