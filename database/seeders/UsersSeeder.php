<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Seed the products table with 50 products
         User::factory()
            ->count(1)
            ->create(
                [
                    'password' => '12345'
                ]
            );
    }
}
