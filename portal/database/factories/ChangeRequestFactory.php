<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Booking;
use App\Models\ChangeRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ChangeRequest>
 */
class ChangeRequestFactory extends Factory
{
    protected $model = ChangeRequest::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'booking_id' => Booking::factory(),
            'request_type' => $this->faker->randomElement(['cancellation', 'date_change', 'name_change', 'upgrade']),
            'requested_by' => $this->faker->randomElement(['customer', 'staff']),
            'reason' => $this->faker->sentence(),
            'status' => $this->faker->randomElement(['open', 'in_progress', 'completed']),
        ];
    }
}
