<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Seed the products table with 50 products
        Product::factory()->count(50)->create();
    }
}
