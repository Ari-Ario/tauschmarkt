<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProductReview;
use App\Models\Product;
use App\Models\User;

class ProductReviewsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $products = Product::all();
        $users = User::all();
        foreach ($products as $product) {
            \App\Models\ProductReview::factory()->count(rand(0, 10))->create([
                'product_id' => $product->id,
                'user_id' => $users->random()->id,
            ]);
        }
    }
}
// composer require fzaninotto/faker
