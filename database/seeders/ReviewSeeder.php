<?php

namespace Database\Seeders;

use App\Models\Review;
use App\Models\Tour;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        $tour = Tour::first();

        Review::create([
            'user_id' => $admin->id,
            'tour_id' => $tour->id,
            'rating' => 5,
            'comment' => 'The Maasai Mara was beyond words. Big Simba Safaris made the whole experience seamless and unforgettable.',
            'is_approved' => true,
        ]);
    }
}
