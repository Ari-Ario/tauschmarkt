<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = \App\Models\User::class;
    public function definition(): array
    {
        return [
            'firstname' => $this->faker->name,
            'lastname' => $this->faker->name,

            'email' => $this->faker->unique()->safeEmail,
            'password' => Hash::make('password'), // default password
            'is_seller' => $this->faker->boolean,
        ];
    }
}
