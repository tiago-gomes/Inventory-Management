<?php

namespace App\Services\Contract;

use App\Models\Supplier;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface SupplierServiceInterface
{
    //public function search(array $search_attributes, array $pagination_attributes): LengthAwarePaginator;

    //public function view(int $supplier_id): Supplier;

    public function create(array $item): Supplier;

    //public function update(int $supplier_id, array $item): Supplier;

    //public function delete(int $id): bool;
}
