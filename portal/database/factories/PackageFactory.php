<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Package;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Package>
 */
class PackageFactory extends Factory
{
    protected $model = Package::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        $title = $this->faker->randomElement([
            'Golden Triangle Tour',
            'Kerala Backwaters Escape',
            'Rajasthan Heritage Trail',
            'Goa Beach Holiday',
            'Himachal Adventure Package',
            'Andaman Island Getaway',
            'South India Temple Tour',
            'Kashmir Paradise Tour',
        ]).' '.$this->faker->numerify('##');

        return [
            'slug' => Str::slug($title).'-'.$this->faker->unique()->numerify('###'),
            'title' => $title,
            'destinations' => $this->faker->randomElement(['Delhi, Agra, Jaipur', 'Kochi, Alleppey, Munnar', 'Jaipur, Jodhpur, Udaipur', 'Goa', 'Manali, Shimla']),
            'departure_city' => $this->faker->randomElement(['Mumbai', 'Delhi', 'Bangalore', 'Chennai', 'Hyderabad']),
            'duration_days' => $this->faker->numberBetween(3, 14),
            'duration_nights' => $this->faker->numberBetween(2, 13),
            'price_from_inr' => $this->faker->randomFloat(2, 15000, 200000),
            'short_description' => $this->faker->sentence(15),
            'long_description' => $this->faker->paragraphs(3, true),
            'highlights' => $this->faker->randomElements([
                'Taj Mahal visit',
                'Houseboat stay',
                'Camel safari',
                'Beach activities',
                'Mountain trekking',
                'Heritage hotel stay',
                'Local cuisine tour',
                'Wildlife safari',
            ], 4),
            'inclusions' => ['Accommodation', 'Breakfast', 'Airport transfers', 'Sightseeing'],
            'exclusions' => ['Flights', 'Personal expenses', 'Travel insurance'],
            'category_tags' => $this->faker->randomElements(['beach', 'heritage', 'adventure', 'honeymoon', 'family', 'luxury'], 2),
            'status' => $this->faker->randomElement(['draft', 'active']),
            'published_at' => $this->faker->optional()->dateTimeBetween('-1 year', 'now'),
        ];
    }

    public function active(): static
    {
        return $this->state(['status' => 'active', 'published_at' => now()]);
    }

    public function draft(): static
    {
        return $this->state(['status' => 'draft', 'published_at' => null]);
    }
}
