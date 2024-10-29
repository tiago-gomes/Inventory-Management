<?php

namespace App\Services;

use App\Models\Supplier;
use App\Models\Product;
use App\Services\Contract\ProductServiceInterface;

class ProductService implements ProductServiceInterface
{
    public function validate(array $item): void
    {
        if (!array_key_exists('name', $item) || empty($item['name'])) {
            throw new \Exception("Name is required and cannot be empty", 422);
        }

        if (!array_key_exists('description', $item) || empty($item['description'])) {
            throw new \Exception("Description is required and cannot be empty", 422);
        }

        if (!array_key_exists('price', $item) || $item['price'] === null) {
            throw new \Exception("Price is required", 422);
        }

        if (!is_numeric($item['price']) || $item['price'] < 0) {
            throw new \Exception("Price must be a non-negative number", 422);
        }
    }

    public function create(array $item): Product
    {

        $this->validate($item);

        // check if product exists
        $product = Product::where('name', $item['name'])->first();
        if ($product) {
            throw new \Exception("Product already exists", 422);
        }

        // check if supplier exists
        $supplier = Supplier::where('id', $item['supplier_id'])->first();
        if (!$supplier) {
            throw new \Exception("Supplier does not exist", 422);
        }

        // check if price is valid
        if ($item['price'] <= 0) {
            throw new \Exception("Invalid price", 422);
        }

        return Product::create($item);
    }
}
