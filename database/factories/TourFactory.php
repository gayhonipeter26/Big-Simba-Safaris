<?php

namespace Database\Factories;

use App\Models\Destination;
use App\Models\Tour;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Tour>
 */
class TourFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->randomElement(['Serengeti', 'Amboseli', 'Maasai Mara', 'Tsavo', 'Ngorongoro']).' '.fake()->randomElement(['Adventure', 'Explorer', 'Classic', 'Wildlife']).' Tour';

        return [
            'destination_id' => Destination::factory(),
            'name' => $name,
            'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(1, 9999),
            'price' => fake()->randomFloat(2, 500, 5000),
            'duration' => fake()->randomElement(['3 days', '5 days', '7 days', '10 days']),
            'itinerary' => [
                ['day' => 1, 'description' => fake()->sentence()],
                ['day' => 2, 'description' => fake()->sentence()],
            ],
            'images' => [fake()->imageUrl(800, 600, 'animals'), fake()->imageUrl(800, 600, 'nature')],
            'whats_included' => [fake()->sentence(), fake()->sentence(), 'Accommodation', 'Meals'],
            'whats_excluded' => ['Flights', 'Visa fees', fake()->sentence()],
        ];
    }
}
