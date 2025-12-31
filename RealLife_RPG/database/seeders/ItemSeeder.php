<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Health Potion
        Item::firstOrCreate(
            ['name' => 'Health Potion'],
            [
                'description' => 'A red bubbling liquid. Restores 15 HP.',
                'image_url' => 'https://example.com/potion.png', // Placeholder or local asset path logic if we had it
                'cost' => 50,
                'type' => 'consumable',
                'effects' => ['hp' => 15],
            ]
        );

        // 2. Streak Freeze
        Item::firstOrCreate(
            ['name' => 'Streak Freeze'],
            [
                'description' => 'Freeze your streak for one day. Protects from damage if you miss a daily.',
                'image_url' => 'https://example.com/ice.png',
                'cost' => 100,
                'type' => 'passive', // or 'consumable' but auto-consumed? Let's call it passive for logic checks
                'effects' => ['streak_freeze' => true],
            ]
        );



        // 4. Scroll of Wisdom (XP Boost)
        Item::firstOrCreate(
            ['name' => 'Scroll of Wisdom'],
            [
                'description' => 'Ancient text that grants 50 XP instantly.',
                'image_url' => 'https://example.com/scroll.png',
                'cost' => 150,
                'type' => 'consumable',
                'effects' => ['exp' => 50],
            ]
        );

        // 5. Golden Apple
        Item::firstOrCreate(
            ['name' => 'Golden Apple'],
            [
                'description' => 'A rare fruit. Fully restores HP.',
                'image_url' => 'https://example.com/apple.png',
                'cost' => 200,
                'type' => 'consumable',
                'effects' => ['hp' => 100], // Assuming 100 is max or full heal logic
            ]
        );
    }
}
