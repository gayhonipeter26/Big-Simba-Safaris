<?php

namespace Database\Seeders;

use App\Models\StrategicEvent;
use Illuminate\Database\Seeder;

class StrategicEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events = [
            [
                'name' => 'Mara Migration',
                'occasion' => 'Great Migration Crossing',
                'month' => 6, // July (0-indexed in JS, but let's keep it 6 to match the logic)
                'days' => [12, 13, 14, 15, 16, 17, 18],
                'color' => 'bg-safari-gold',
                'label' => 'Peak',
                'location' => 'Maasai Mara',
                'region' => 'Narok County, Kenya',
                'time' => '06:00 - 18:30 HRS',
                'description' => 'Synchronized crossing of the Mara River discovered. Peak predator activity detected across the northern sector.',
                'asset_class' => 'ALPHA EXPEDITION',
                'image' => 'https://images.unsplash.com/photo-1516426122078-c23e76319801?q=80&w=800',
                'status' => 'published',
            ],
            [
                'name' => 'Amboseli Calving',
                'occasion' => 'Elephant Calving Window',
                'month' => 2, // March
                'days' => [24, 25, 26, 27, 28],
                'color' => 'bg-green-500',
                'label' => 'Event',
                'location' => 'Amboseli Park',
                'region' => 'Kajiado, Kenya',
                'time' => '07:00 - 17:00 HRS',
                'description' => 'Synchronized elephant calving detected. Large matriarchal herds converging at the wetlands for hydration.',
                'asset_class' => 'TACTICAL SURVEY',
                'image' => 'https://images.unsplash.com/photo-1557050543-4d5f4e07ef46?q=80&w=800',
                'status' => 'published',
            ],
            [
                'name' => 'Tsavo Recon',
                'occasion' => 'Arid Reconnaisance',
                'month' => 7, // August
                'days' => [1, 2, 3, 4, 5],
                'color' => 'bg-orange-500',
                'label' => 'Optimal',
                'location' => 'Tsavo East',
                'region' => 'Coast Region, Kenya',
                'time' => '05:30 - 19:00 HRS',
                'description' => 'Extreme arid conditions forcing predator convergence at fixed watering points.',
                'asset_class' => 'SURVEY RECON',
                'image' => 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?q=80&w=800',
                'status' => 'published',
            ],
        ];

        foreach ($events as $event) {
            StrategicEvent::create($event);
        }
    }
}
