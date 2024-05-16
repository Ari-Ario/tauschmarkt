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
        $categories = [
            ['name' => 'Electronics', 'description' => ''],
            ['name' => 'Clothing', 'description' => ''],
            // Add more categories here
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
