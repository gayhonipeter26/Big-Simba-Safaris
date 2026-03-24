<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Tour;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tour_id' => Tour::factory(),
            'user_id' => User::factory(),
            'booking_date' => fake()->dateTimeBetween('now', '+6 months')->format('Y-m-d'),
            'number_of_people' => fake()->numberBetween(1, 10),
            'total_price' => fake()->randomFloat(2, 500, 10000),
            'payment_status' => fake()->randomElement(['pending', 'paid', 'refunded']),
            'booking_confirmation_code' => strtoupper(fake()->lexify('????????')),
            'status' => fake()->randomElement(['pending', 'confirmed', 'completed', 'cancelled']),
        ];
    }

    public function confirmed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'confirmed',
            'payment_status' => 'paid',
        ]);
    }
}
