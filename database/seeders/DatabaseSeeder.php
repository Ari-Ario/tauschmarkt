<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       
        \App\Models\User::create([
            'firstname' => 'Gest',
            'lastname' => 'Gast',
            'email' => 'gest@info.ch',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);
        // \App\Models\User::create([
        //     'firstname' => 'Reto',
        //     'lastname' => 'Stam',
        //     'email' => 'reto@stam.com',
        //     'email_verified_at' => now(),
        //     'password' => Hash::make('password'),
        // ]);

        $this->call([
            UsersTableSeeder::class,
        ]);
        $this->call([
            CategoriesTableSeeder::class,
        ]);
        $this->call([
            ProductsTableSeeder::class,
        ]);

        $this->call([

            OrdersTableSeeder::class,
            OrderItemsTableSeeder::class,
            ProductReviewsTableSeeder::class,
        ]);

        $this->call([
            FavoritesSeeder::class,
            // Add other seeders here
        ]);
    }
}
