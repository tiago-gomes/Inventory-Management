<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Supplier;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Supplier>
 */
class SupplierFactory extends Factory
{
    protected $model = Supplier::class;

    public function definition()
    {
        return [
            'name' => fake()->company,
            'address' => fake()->address,
            'email' => fake()->unique()->safeEmail,
            'phone' => fake()->phoneNumber,
            'mobile' => fake()->phoneNumber,
            'fax' => fake()->optional()->phoneNumber,
        ];
    }
}
