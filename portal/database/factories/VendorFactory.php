<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Vendor>
 */
class VendorFactory extends Factory
{
    protected $model = Vendor::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'code' => strtoupper($this->faker->unique()->lexify('VND-???')),
            'contact_person' => $this->faker->name(),
            'email' => $this->faker->unique()->companyEmail(),
            'phone' => '+91 '.$this->faker->numerify('9#########'),
            'gstin' => null,
            'address' => $this->faker->address(),
            'payment_terms_days' => $this->faker->randomElement([0, 7, 15, 30, 45]),
            'notes' => null,
        ];
    }

    public function withGstin(): static
    {
        return $this->state(function () {
            $stateCode = $this->faker->numberBetween(10, 37);
            $pan = strtoupper(
                $this->faker->lexify('?????').
                $this->faker->numerify('####').
                $this->faker->lexify('?'),
            );

            return [
                'gstin' => strtoupper($stateCode.$pan.$this->faker->numerify('#').'Z'.$this->faker->lexify('?')),
            ];
        });
    }
}
