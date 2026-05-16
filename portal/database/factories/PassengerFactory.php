<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Passenger;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Passenger>
 */
class PassengerFactory extends Factory
{
    protected $model = Passenger::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'title' => $this->faker->randomElement(['Mr', 'Mrs', 'Ms', 'Dr']),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'dob' => $this->faker->dateTimeBetween('-60 years', '-18 years')->format('Y-m-d'),
            'nationality' => 'Indian',
            'passport_number' => strtoupper($this->faker->bothify('??#######')),
            'passport_expiry' => $this->faker->dateTimeBetween('+1 year', '+10 years')->format('Y-m-d'),
        ];
    }
}
