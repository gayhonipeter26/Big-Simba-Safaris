<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();

        Post::create([
            'user_id' => $admin->id,
            'title' => 'The Great Migration: A Spectacle of Life',
            'slug' => 'the-great-migration-a-spectacle-of-life',
            'content' => 'The Great Migration is one of the most incredible natural events on the planet. Millions of wildebeest and zebra traverse the Serengeti-Mara ecosystem...',
            'image' => 'https://images.unsplash.com/photo-1516422213484-21437ef33ebb?q=80&w=2670',
            'is_published' => true,
        ]);

        Post::create([
            'user_id' => $admin->id,
            'title' => 'Secret Spots of Diani Beach',
            'slug' => 'secret-spots-of-diani-beach',
            'content' => 'Beyond the main tourist areas, Diani Beach holds many secrets. From hidden coral reefs to quiet mangrove forests...',
            'image' => 'https://images.unsplash.com/photo-1589315834339-ad7481ecfed9?q=80&w=2124',
            'is_published' => true,
        ]);
    }
}
