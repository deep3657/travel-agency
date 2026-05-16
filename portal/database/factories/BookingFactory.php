<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Trip;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Booking>
 */
class BookingFactory extends Factory
{
    protected $model = Booking::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        $customer = Customer::factory()->create();

        return [
            'trip_id' => Trip::factory()->create(['customer_id' => $customer->id])->id,
            'customer_id' => $customer->id,
            'booking_type' => $this->faker->randomElement(['flight', 'hotel', 'package']),
            'sale_amount' => $this->faker->randomFloat(2, 10000, 200000),
            'purchase_cost' => $this->faker->randomFloat(2, 8000, 150000),
            'payment_status' => $this->faker->randomElement(['unpaid', 'partial', 'paid']),
            'status' => $this->faker->randomElement(['pending_confirmation', 'confirmed', 'completed']),
        ];
    }
}
