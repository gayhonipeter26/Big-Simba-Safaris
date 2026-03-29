<?php

namespace Database\Seeders;

use App\Models\Destination;
use App\Models\Tour;
use Illuminate\Database\Seeder;

class TourSeeder extends Seeder
{
    public function run(): void
    {
        $mara = Destination::updateOrCreate(
            ['slug' => 'maasai-mara'],
            [
                'name' => 'Maasai Mara',
                'overview' => 'Experience the world-famous wildlife sanctuary.',
                'things_to_do' => ['Game drive', 'Hot air balloon', 'Maasai village visit'],
                'best_time_to_visit' => 'July to October (Great Migration)',
                'image' => 'https://images.unsplash.com/photo-1547471080-7cc2caa01a7e?q=80&w=2071',
            ]
        );

        $diani = Destination::updateOrCreate(
            ['slug' => 'diani-beach'],
            [
                'name' => 'Diani Beach',
                'overview' => 'Pristine white sands and turquoise waters.',
                'things_to_do' => ['Skydiving', 'Snorkeling', 'Deep sea fishing'],
                'best_time_to_visit' => 'December to March',
                'image' => 'https://images.unsplash.com/photo-1589315834339-ad7481ecfed9?q=80&w=2124',
            ]
        );

        $amboseli = Destination::updateOrCreate(
            ['slug' => 'amboseli'],
            [
                'name' => 'Amboseli',
                'overview' => 'The land of giants at the foot of Mount Kilimanjaro.',
                'things_to_do' => ['Elephant tracking', 'Views of Kilimanjaro', 'Nature walks'],
                'best_time_to_visit' => 'June to October',
                'image' => 'https://images.unsplash.com/photo-1516422213484-21437ef33ebb?q=80&w=2670',
            ]
        );

        $nairobi = Destination::updateOrCreate(
            ['slug' => 'nairobi-city'],
            [
                'name' => 'Nairobi City',
                'overview' => 'The vibrant "Green City in the Sun", where urban life meets the wild.',
                'things_to_do' => ['Elephant Orphanage', 'Giraffe Centre', 'Museum visit'],
                'best_time_to_visit' => 'Year-round',
                'image' => 'https://images.unsplash.com/photo-1629197520635-16570f00994f?q=80&w=2124',
            ]
        );

        Tour::updateOrCreate(
            ['slug' => '3-days-maasai-mara-express'],
            [
                'destination_id' => $mara->id,
                'name' => '3 Days Maasai Mara Express',
                'price' => 58500.00,
                'duration' => '3 Days',
                'itinerary' => [
                    ['day' => 1, 'title' => 'The Journey Begins', 'description' => 'Nairobi to Maasai Mara, evening game drive.'],
                    ['day' => 2, 'title' => 'Savanna Mastery', 'description' => 'Full day game drive with picnic lunch.'],
                    ['day' => 3, 'title' => 'Dawn Farewell', 'description' => 'Morning game drive and return to Nairobi.'],
                ],
                'images' => ['https://images.unsplash.com/photo-1547471080-7cc2caa01a7e?q=80&w=2071'],
                'whats_included' => ['Transport', 'Park fees', 'Professional guide', 'Meals'],
                'whats_excluded' => ['Tips', 'Personal items', 'Visas'],
            ]
        );

        Tour::updateOrCreate(
            ['slug' => '5-days-diani-beach-relaxation'],
            [
                'destination_id' => $diani->id,
                'name' => '5 Days Diani Beach Relaxation',
                'price' => 97500.00,
                'duration' => '5 Days',
                'itinerary' => [
                    ['day' => 1, 'title' => 'Coastal Arrival', 'description' => 'Arrival at Diani Beach.'],
                    ['day' => 2, 'title' => 'Tidal Rhythms', 'description' => 'Beach relaxation and sunset dhow cruise.'],
                    ['day' => 3, 'title' => 'Marine Sanctuary', 'description' => 'Full day snorkeling at Kisite-Mpunguti.'],
                    ['day' => 4, 'title' => 'Azure Flight', 'description' => 'Optional skydiving or spa day.'],
                    ['day' => 5, 'title' => 'Ocean Parting', 'description' => 'Departure.'],
                ],
                'images' => ['https://images.unsplash.com/photo-1589315834339-ad7481ecfed9?q=80&w=2124'],
                'whats_included' => ['Accommodation', 'Airport transfers', 'Half-board meals', 'Marine park fees'],
                'whats_excluded' => ['Alcoholic drinks', 'Tips', 'Flights'],
            ]
        );

        Tour::updateOrCreate(
            ['slug' => '4-days-amboseli-giant-quest'],
            [
                'destination_id' => $amboseli->id,
                'name' => '4 Days Amboseli Giant Quest',
                'price' => 75400.00,
                'duration' => '4 Days',
                'itinerary' => [
                    ['day' => 1, 'title' => 'Shadow of the Mountain', 'description' => 'Nairobi to Amboseli, check-in and evening game drive.'],
                    ['day' => 2, 'title' => 'The Tusked Parade', 'description' => 'Full day elephant tracking and nature walks.'],
                    ['day' => 3, 'title' => 'Kilimanjaro Dawn', 'description' => 'Sunrise views and cultural visit.'],
                    ['day' => 4, 'title' => 'Final Trek', 'description' => 'Breakfast and return to Nairobi.'],
                ],
                'images' => ['https://images.unsplash.com/photo-1516422213484-21437ef33ebb?q=80&w=2670'],
                'whats_included' => ['4x4 Transport', 'Park entry', 'Local scout', 'Boutique lodging'],
                'whats_excluded' => ['Laundry', 'Premium spirits', 'Personal insurance'],
            ]
        );

        Tour::updateOrCreate(
            ['slug' => '2-days-nairobi-wilderness-city-tour'],
            [
                'destination_id' => $nairobi->id,
                'name' => '2 Days Nairobi Wilderness City Tour',
                'price' => 41600.00,
                'duration' => '2 Days',
                'itinerary' => [
                    ['day' => 1, 'title' => 'The Orphanage & Giraffes', 'description' => 'Visit Sheldrick Wildlife Trust and Giraffe Centre.'],
                    ['day' => 2, 'title' => 'The Wild City', 'description' => 'Morning game drive in Nairobi National Park and airport drop-off.'],
                ],
                'images' => ['https://images.unsplash.com/photo-1629197520635-16570f00994f?q=80&w=2124'],
                'whats_included' => ['Park fees', 'Transport', 'Lunch at Carnivore'],
                'whats_excluded' => ['Flights', 'Dinner', 'Tips'],
            ]
        );
    }
}
