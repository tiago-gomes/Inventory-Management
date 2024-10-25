<?php

namespace Database\Factories;

use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class InventoryFactory extends Factory
{
    protected $model = Inventory::class;

    public function definition()
    {
        return [
            'product_id' => Product::factory(), // Creates a new Product for reference
            'quantity' => $this->faker->numberBetween(0, 100),
            'threshold' => $this->faker->numberBetween(1, 20),
        ];
    }
}
