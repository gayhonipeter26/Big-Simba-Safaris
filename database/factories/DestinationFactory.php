<?php

namespace Database\Factories;

use App\Models\Destination;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Destination>
 */
class DestinationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->city().' '.fake()->randomElement(['Safari', 'Reserve', 'National Park', 'Wilderness']);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'overview' => fake()->paragraphs(2, true),
            'things_to_do' => [fake()->sentence(), fake()->sentence(), fake()->sentence()],
            'best_time_to_visit' => fake()->randomElement(['June to October', 'November to March', 'Year-round']),
            'image' => fake()->imageUrl(800, 600, 'nature'),
        ];
    }
}
