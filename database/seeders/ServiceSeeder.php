<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'name' => 'Hostel Accommodation',
                'slug' => 'hostel-accommodation',
                'category' => 'hostel',
                'description' => 'Elite wild-zone lodging with tactical supply lines and thermal containment. Perfect for scouts on a frontier mission.',
                'pricing_tier' => 'Per Night',
                'starting_price' => 25.00,
                'features' => json_encode(['Secure Perimeter', 'Ration Supply', 'Wilderness Access']),
            ],
            [
                'name' => 'Tactical Game Drive',
                'slug' => 'tactical-game-drive',
                'category' => 'game_drive',
                'description' => 'Advanced reconnaissance missions in heavy-terrain assets. Track the Big Five with elite navigators.',
                'pricing_tier' => 'Per Unit',
                'starting_price' => 150.00,
                'features' => json_encode(['4x4 Deployment', 'Expert Scout', 'Night Thermal Op']),
            ],
            [
                'name' => 'Wilderness Transfer',
                'slug' => 'wilderness-transfer',
                'category' => 'transfer',
                'description' => 'Rapid troop deployment between wild zones and evacuation points. High-speed, high-reliability extraction.',
                'pricing_tier' => 'One Way',
                'starting_price' => 80.00,
                'features' => json_encode(['Airport Pickup', 'Border Logistics', 'Elite Transport']),
            ],
            [
                'name' => 'Safari Induction',
                'slug' => 'safari-induction',
                'category' => 'other',
                'description' => 'Complete safari survival training and mission briefing for domestic and international scouts.',
                'pricing_tier' => 'Complete Training',
                'starting_price' => 45.00,
                'features' => json_encode(['Survival Gear', 'Tactical Maps', 'Wild Training']),
            ],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(['slug' => $service['slug']], $service);
        }
    }
}
