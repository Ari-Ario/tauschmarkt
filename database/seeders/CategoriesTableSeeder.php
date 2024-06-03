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
        $categories = [
            ['name' => 'Obst &Gemüse', 'description' => ''],
            ['name' => 'Honigprodukte', 'description' => ''],
            ['name' => 'Milchprodukte', 'description' => ''],
            ['name' => 'Getränke & Wienkeller', 'description' => ''],
            ['name' => 'Fleisch', 'description' => ''],
            ['name' => 'Home & Kitchen', 'description' => ''],
            ['name' => 'Health & Beauty', 'description' => ''],
            ['name' => 'Blumen & Gärtnerei', 'description' => ''],
            ['name' => 'überraschungspäckli', 'description' => ''],
            ['name' => 'Andere Kategorien', 'description' => ''],

            // Add more categories here
        ];

        foreach ($categories as $category) {
            \App\Models\Category::create($category);
        }
    }
}
