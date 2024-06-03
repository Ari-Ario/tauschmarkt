<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = \App\Models\Product::class;
    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'amount' => $this->faker->randomFloat(2, 10, 500),
            'quantity' => $this->faker->randomFloat(2, 10, 500),
            'published'=>$this->faker->boolean,
            'inStock'=>$this->faker->boolean,
            'price' => $this->faker->randomFloat(2, 10, 500),
            'category_id' => Category::factory(),
            'seller_id' => User::factory(),
        ];
    }
}
