<?php

namespace Database\Seeders;

use App\Models\GalleryItem;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        GalleryItem::create([
            'title' => 'The Great Migration',
            'type' => 'image',
            'url' => 'https://images.unsplash.com/photo-1516426122078-c23e76319801?q=80&w=2068',
            'description' => 'A breathtaking view of the wildebeest crossing the Mara River.',
            'is_featured' => true,
        ]);

        GalleryItem::create([
            'title' => 'King of the Savanna',
            'type' => 'image',
            'url' => 'https://images.unsplash.com/photo-1549480017-d76466a4b7e8?q=80&w=2048',
            'description' => 'A majestic lion overlooking his territory in the Maasai Mara.',
            'is_featured' => true,
        ]);

        GalleryItem::create([
            'title' => 'Gentle Giants',
            'type' => 'image',
            'url' => 'https://images.unsplash.com/photo-1547471080-7cc2caa01a7e?q=80&w=2071',
            'description' => 'Elephants wandering through the vast plains of Amboseli.',
            'is_featured' => true,
        ]);

        GalleryItem::create([
            'title' => 'Leopard in the Shadows',
            'type' => 'image',
            'url' => 'https://images.unsplash.com/photo-1523805009345-7448845a9e53?q=80&w=2072',
            'description' => 'A leopard blending into the acacia trees of Samburu.',
            'is_featured' => true,
        ]);
    }
}
