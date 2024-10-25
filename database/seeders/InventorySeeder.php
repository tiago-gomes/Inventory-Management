<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inventory;

class InventorySeeder extends Seeder
{
    public function run()
    {
        Inventory::factory()->count(50)->create(); // Generates 50 inventory records
    }
}
