<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Category::factory()->count(10)->create();
/*         $categories = [
            ['name' => 'Electronics', 'description' => ''],
            ['name' => 'Clothing', 'description' => ''],
            ['name' => 'Electronics', 'description' => ''],
            ['name' => 'Clothing', 'description' => ''],
            ['name' => 'Books', 'description' => ''],
            ['name' => 'Home & Kitchen', 'description' => ''],
            ['name' => 'Health & Beauty', 'description' => ''],
            ['name' => 'Toys & Games', 'description' => ''],
            ['name' => 'Sports & Outdoors', 'description' => ''],
            ['name' => 'Automotive', 'description' => ''],
            ['name' => 'Music', 'description' => ''],
            ['name' => 'Food & Grocery', 'description' => ''],
            // Add more categories here
        ];

        foreach ($categories as $category) {
            \App\Models\Category::create($category);
        } */
    }
}
