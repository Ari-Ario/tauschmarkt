<?php

namespace Database\Factories;

use App\Models\Favorites;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Favorites>
 */
class FavoritesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Favorites::class;
    public function definition(): array
    {
        return [
            //
            'is_favorite' => $this->faker->boolean,
        ];
    }
}
