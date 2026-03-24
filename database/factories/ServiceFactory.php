<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);

        return [
            'name' => $name,
            'slug' => Str::slug($name).'-'.fake()->numerify('####'),
            'category' => fake()->randomElement(['logistics', 'hospitality', 'safety', 'entertainment']),
            'description' => fake()->paragraph(),
            'image' => fake()->imageUrl(800, 600),
            'pricing_tier' => fake()->randomElement(['basic', 'standard', 'premium']),
            'starting_price' => fake()->randomFloat(2, 50, 500),
            'features' => fake()->sentence(),
        ];
    }
}
