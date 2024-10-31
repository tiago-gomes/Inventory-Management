<?php

namespace App\Services;

use App\Models\Supplier;
use App\Models\Product;
use App\Services\Contract\ProductServiceInterface;
use Illuminate\Pagination\LengthAwarePaginator;

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

    public function checkIfProductExists(string $name)
    {
        // check if product exists
        $product = Product::where('name', $name)->first();
        if ($product) {
            throw new \Exception("Product already exists", 422);
        }
    }

    public function checkIfSupplierExists(int $supplier_id)
    {
        $supplier = Supplier::where('id', $supplier_id)->first();
        if (!$supplier) {
            throw new \Exception("Supplier does not exist", 422);
        }
    }

    public function search(array $search_attributes, array $pagination_attributes): LengthAwarePaginator
    {
        // Set default pagination values if they're not set or not numeric
        $pagination_attributes['per_page'] = isset($pagination_attributes['per_page']) && is_numeric($pagination_attributes['per_page'])
            ? (int) $pagination_attributes['per_page']
            : 15;

        $pagination_attributes['page'] = isset($pagination_attributes['page']) && is_numeric($pagination_attributes['page'])
            ? (int) $pagination_attributes['page']
            : 1;

        // Set default search attributes
        $search_attributes['order_by'] = isset($search_attributes['order_by']) && strtolower($search_attributes['order_by'])=='asc' && strtolower($search_attributes['order_by'])=='desc'
            ? $search_attributes['order_by']
            : 'asc';

        $search_attributes['order_field'] = isset($search_attributes['order_field'])
            ? $search_attributes['order_field']
            : 'id';

        switch ($search_attributes['order_field']) {
            case 'name':
                $search_attributes['order_field'] = 'name';
                break;
            case 'description':
                $search_attributes['order_field'] = 'description';
                break;
            case 'price':
                $search_attributes['order_field'] = 'description';
                break;
            case 'supplier_name':
                $search_attributes['order_field'] = 'supplier_name';
                break;
            default:
                $search_attributes['order_field'] = 'id';
                break;
        }

        // search the products table or associate tables.
        $products = Product::query()
            ->when($search_attributes['name'] ?? null,function ($query, $name) {
                return $query->where('name', $name);
            })
            ->when($search_attributes['description'] ?? null,function ($query, $description) {
                return $query->where('description', $description);
            })
            ->when($search_attributes['price'] ?? null,function ($query, $price) {
                return $query->where('price', $price);
            })
            ->when($search_attributes['supplier_name'] ?? null,function ($query, $supplier_name) {
                return $query->whereHas('supplier', function ($query) use ($supplier_name) {
                    $query->where('name', $supplier_name);
                });
            })
            ->orderBy($search_attributes['order_field'], $search_attributes['order_by'])
            ->paginate(
                $pagination_attributes['per_page'],
                ['*'],
                'page',
                $pagination_attributes['page']
            );

        // return colletions of products found per page
        return $products;
    }

    public function create(array $item): Product
    {
        // validate Item
        $this->validate($item);

        // check if product exists
        $this->checkIfProductExists($item['name']);

        // check if supplier exists
        $this->checkIfSupplierExists($item['supplier_id']);

        // create product
        return Product::create($item);
    }

    public function update(int $product_id, array $item): Product
    {
        // validate item
        $this->validate($item);

        // check if product exists
        $this->checkIfProductExists($item['name']);

        // check if supplier exists
        $this->checkIfSupplierExists($item['supplier_id']);

        /** * @var Product $product */
        $product = Product::find($product_id);
        if (!$product) {
            throw new \Exception("Product does not exist", 422);
        }

        // trying to update product
        $updated = $product->update($item);
        if (!$updated) {
            throw new \Exception("Failed to update product", 500);
        }

        return $product;
    }

    public function view(int $product_id): Product
    {
        /** * @var Product $product */
        $product = Product::find($product_id);
        if (!$product) {
            throw new \Exception("Product does not exist", 422);
        }
        return $product;
    }

    public function delete(int $product_id): bool
    {
        /** * @var Product $product */
        $product = Product::find($product_id);
        if (!$product) {
            throw new \Exception("Product does not exist", 422);
        }

        $delete = $product->delete();
        if (!$delete) {
            throw new \Exception("Failed to delete product", 500);
        }

        return $delete;
    }
}
