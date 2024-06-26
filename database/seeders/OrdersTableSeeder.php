<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\User;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Order::factory()->count(10)->create([
            'user_id' => User::inRandomOrder()->first()->id,
            'seller_id' => User::inRandomOrder()->first()->id,
        ]);
    }
}
