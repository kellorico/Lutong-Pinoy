<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('menus')->insert([
            [
                'image_path' => 'https://assets.unileversolutions.com/recipes-v3/230383-default.jpg',
                'menu_name' => 'Spaghetti Bolognese',
                'category_id' => 1, // Ensure category with ID 1 exists
                'description' => 'Classic Italian pasta dish with a rich and savory meat sauce.',
                'price' => 12.50,
                'availability' => 'Available',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'image_path' => 'https://assets.unileversolutions.com/recipes-v3/230383-default.jpg',
                'menu_name' => 'Caesar Salad',
                'category_id' => 2, // Ensure category with ID 2 exists
                'description' => 'Crisp romaine lettuce with creamy Caesar dressing, croutons, and parmesan.',
                'price' => 8.75,
                'availability' => 'Available',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'image_path' => 'https://assets.unileversolutions.com/recipes-v3/230383-default.jpg',
                'menu_name' => 'Grilled Chicken Sandwich',
                'category_id' => 3, // Ensure category with ID 3 exists
                'description' => 'Juicy grilled chicken with lettuce, tomato, and aioli on a toasted bun.',
                'price' => 10.00,
                'availability' => 'Unavailable',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
