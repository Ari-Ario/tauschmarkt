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
        // \App\Models\Category::factory()->count(10)->create();
        $categories = [
            ['name' => 'Obst & Gemüse', 'description' => ''],
            ['name' => 'Milchprodukte', 'description' => ''],
            ['name' => 'Vollkornprodukte', 'description' => ''],
            ['name' => 'Honigprodukte', 'description' => ''],
            ['name' => 'Fleisch & Geflügel', 'description' => ''],
            ['name' => 'Getränke und Wein', 'description' => ''],
            ['name' => 'Blumen & Gärtnerei', 'description' => ''],
            ['name' => 'Überraschungspäckli', 'description' => ''],
            ['name' => 'Andere Kategorien', 'description' => ''],

            // Add more categories here
        ];

        foreach ($categories as $category) {
            \App\Models\Category::create($category);
        }
    }
}
