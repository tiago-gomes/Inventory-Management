<?php

namespace App\Services\Contract;

use App\Models\Product;
use Illuminate\Support\Collection;

interface ProductServiceInterface
{
    //public function getAllProducts(array $pagination): Collection;

    public function create(array $item): Product;

    //public function update(int $id, array $item): Product;

    //public function delete(int $id): bool;
}
