<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Favorites;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FavoritesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('favorites')->insert([
            [
                'first_user_id' => 1,
                'user_id' => 1,
                'is_favorite' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_user_id' => 2,
                'user_id' => 1,
                'is_favorite' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_user_id' => 1,
                'user_id' => 2,
                'is_favorite' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more seed data as needed
        ]);
        Favorites::factory()->count(10)->create();

        //
    }
}
