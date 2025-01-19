<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            ['category_name' => 'Main Course', 'created_at' => now(), 'updated_at' => now()],
            ['category_name' => 'Salads', 'created_at' => now(), 'updated_at' => now()],
            ['category_name' => 'Sandwiches', 'created_at' => now(), 'updated_at' => now()],
            ['category_name' => 'Desserts', 'created_at' => now(), 'updated_at' => now()],
            ['category_name' => 'Beverages', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
