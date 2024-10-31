<?php

namespace App\Services\Contract;

use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ProductServiceInterface
{
    public function search(array $search_attributes, array $pagination_attributes): LengthAwarePaginator;

    //public function view(int $product_id): Product;

    public function create(array $item): Product;

    public function update(int $product_id, array $item): Product;

    public function delete(int $id): bool;
}
