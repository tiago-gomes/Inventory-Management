<?php

namespace App\Services\Contract;

use App\Models\Product;
use Illuminate\Support\Collection;

interface ProductServiceInterface
{
    //public function search(array $pagination): Collection;

    //public function view(int $product_id): Product;

    public function create(array $item): Product;

    public function update(int $product_id, array $item): Product;

    public function delete(int $id): bool;
}
