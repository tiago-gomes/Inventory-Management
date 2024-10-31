<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Services\ProductService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;
use App\Models\Supplier;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductServiceTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    private ProductService $productService;

    protected function setUp(): void
    {
        parent::setUp();

        // Instantiate ProductService
        $this->productService = new ProductService();
    }

    public function test_validate_throws_exception_when_name_is_missing()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Name is required and cannot be empty");

        $item = [
            'description' => 'Test description',
            'price' => 100
        ];

        $this->productService->validate($item);
    }

    public function test_validate_throws_exception_when_description_is_missing()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Description is required and cannot be empty");

        $item = [
            'name' => 'Test Product',
            'price' => 100
        ];

        $this->productService->validate($item);
    }

    public function test_validate_throws_exception_when_price_is_missing()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Price is required");

        $item = [
            'name' => 'Test Product',
            'description' => 'Test description'
        ];

        $this->productService->validate($item);
    }

    public function test_validate_throws_exception_when_price_is_negative()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Price must be a non-negative number");

        $item = [
            'name' => 'Test Product',
            'description' => 'Test description',
            'price' => -10
        ];

        $this->productService->validate($item);
    }

    public function test_validate_throws_exception_when_price_is_non_numeric()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Price must be a non-negative number");

        $item = [
            'name' => 'Test Product',
            'description' => 'Test description',
            'price' => 'not a number'
        ];

        $this->productService->validate($item);
    }

    public function test_validate_passes_for_valid_data()
    {
        $item = [
            'name' => 'Test Product',
            'description' => 'Test description',
            'price' => 100
        ];

        $this->productService->validate($item);

        $this->assertTrue(true, "Validation passed for valid data.");
    }

    public function test_create_product_successfully()
    {
        $supplier = Supplier::factory()->create();

        // Arrange: Prepare data for a new product
        $productData = [
            'name' => 'New Product',
            'description' => 'New Description',
            'price' => 100.00,
            'supplier_id' => $supplier->id
        ];

        // Act: Call the create method
        $product = $this->productService->create($productData);

        // Assert: Verify the product was created
        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals('New Product', $product->name);
        $this->assertEquals(100.00, $product->price);
        $this->assertEquals($supplier->id, $product->supplier_id);
    }

    public function test_create_product_throws_exception_if_product_exists()
    {
        // Arrange: Prepare data for an existing product
        $existingProduct = Product::factory()->create([
            'name' => 'Existing Product',
            'description' => 'Existing Description',
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Product already exists");

        // Act: Try to create a product with the same name
        $this->productService->create([
            'name' => 'Existing Product',
            'description' => 'Existing Description',
            'price' => 100.00,
        ]);
    }

    public function test_create_product_throws_exception_if_supplier_does_not_exist()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Supplier does not exist");

        $this->productService->create([
            'name' => 'New Product',
            'description' => 'Description Product',
            'price' => 100.00,
            'supplier_id' => 9999, // Assuming this ID does not exist
        ]);
    }

    public function test_product_successful_update()
    {
        $supplier = Supplier::factory()->create();

        // Arrange: Create a product using the factory
        $product = Product::factory()->create([
            'name' => 'Old Product',
            'description' => 'Old description',
            'price' => 10,
            'supplier_id' => $supplier->id
        ]);

        // Act: Update the product
        $item = [
            'name' => 'Updated Product',
            'description' => 'Old description',
            'price' => 10,
            'supplier_id' => $supplier->id,
        ];

        $updatedProduct = $this->productService->update($product->id, $item);

        // Assert: Verify the product is updated
        $this->assertInstanceOf(Product::class, $updatedProduct);
        $this->assertEquals('Updated Product', $updatedProduct->name);
    }

    public function test_product_does_not_exist_when_trying_to_update()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Product does not exist");

        $supplier = Supplier::factory()->create();

        // Act: Update the product
        $item = [
            'name' => 'Updated Product',
            'description' => 'Old description',
            'price' => 10,
            'supplier_id' => $supplier->id,
        ];

        $this->productService->update(999, $item);
    }

    public function test_successful_delete()
    {
        // Arrange: Create a product using the factory
        $product = Product::factory()->create();

        // Act: Attempt to delete the product
        $result = $this->productService->delete($product->id);

        // Assert: Verify that delete returns true and the product no longer exists
        $this->assertTrue($result);
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_product_not_found_when_deleting()
    {
        // Expect an exception for a non-existent product
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Product does not exist");

        // Act: Attempt to delete a non-existing product
        $this->productService->delete(9999); // Assuming 9999 does not exist
    }

    public function test_search_applies_default_pagination()
    {
        Product::factory()->count(20)->create(); // Creating sample products

        $searchAttributes = [];
        $paginationAttributes = [];

        $result = $this->productService->search($searchAttributes, $paginationAttributes);

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertEquals(15, $result->perPage());
        $this->assertEquals(1, $result->currentPage());
    }

    public function test_search_filters_by_name()
    {
        Product::factory()->create(['name' => 'Product1']);
        Product::factory()->count(5)->create(); // Other products

        $searchAttributes = ['name' => 'Product1'];
        $paginationAttributes = ['per_page' => 5, 'page' => 1];

        $result = $this->productService->search($searchAttributes, $paginationAttributes);

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);

        foreach ($result->items() as $item) {
            $this->assertEquals('Product1', $item->name);
        }
    }

    public function test_search_filters_by_description()
    {
        Product::factory()->create(['description' => 'High-quality product']);
        Product::factory()->count(5)->create();

        $searchAttributes = ['description' => 'High-quality product'];
        $paginationAttributes = ['per_page' => 5, 'page' => 1];

        $result = $this->productService->search($searchAttributes, $paginationAttributes);

        foreach ($result->items() as $item) {
            $this->assertEquals('High-quality product', $item->description);
        }
    }

    public function test_search_filters_by_price()
    {
        Product::factory()->create(['price' => 99.99]);
        Product::factory()->count(5)->create();

        $searchAttributes = ['price' => 99.99];
        $paginationAttributes = [];

        $result = $this->productService->search($searchAttributes, $paginationAttributes);

        foreach ($result->items() as $item) {
            $this->assertEquals(99.99, $item->price);
        }
    }

    public function test_search_filters_by_supplier_name()
    {
        $supplier = Supplier::factory()->create(['name' => 'Supplier1']);
        Product::factory()->for($supplier, 'supplier')->create();
        Product::factory()->count(5)->create();

        $searchAttributes = ['supplier_name' => 'Supplier1'];
        $paginationAttributes = [];

        $result = $this->productService->search($searchAttributes, $paginationAttributes);

        foreach ($result->items() as $item) {
            $this->assertEquals('Supplier1', $item->supplier->name);
        }
    }

    public function test_search_filters_by_multiple_attributes()
    {
        $supplier = Supplier::factory()->create(['name' => 'Supplier1']);
        Product::factory()->create([
            'name' => 'Product1',
            'price' => 99.99,
            'supplier_id' => $supplier->id
        ]);
        Product::factory()->count(5)->create();

        $searchAttributes = [
            'name' => 'Product1',
            'price' => 99.99,
            'supplier_name' => 'Supplier1'
        ];
        $paginationAttributes = [];

        $result = $this->productService->search($searchAttributes, $paginationAttributes);

        foreach ($result->items() as $item) {
            $this->assertEquals('Product1', $item->name);
            $this->assertEquals(99.99, $item->price);
            $this->assertEquals('Supplier1', $item->supplier->name);
        }
    }

    public function test_search_handles_invalid_pagination_values()
    {
        Product::factory()->count(20)->create();

        $searchAttributes = [];
        $paginationAttributes = ['per_page' => 'invalid', 'page' => 'invalid'];

        $result = $this->productService->search($searchAttributes, $paginationAttributes);

        $this->assertEquals(15, $result->perPage()); // Should default to 15
        $this->assertEquals(1, $result->currentPage()); // Should default to 1
    }

    public function test_search_applies_default_ordering_by_id()
    {
        Product::factory()->count(10)->create();

        $searchAttributes = []; // No ordering attributes
        $paginationAttributes = [];

        $result = $this->productService->search($searchAttributes, $paginationAttributes);

        // Assert that the results are ordered by 'id' ascending
        $items = $result->items();
        $ids = array_column($items, 'id'); // Extract ids from the items

        $sortedIds = $ids;
        sort($sortedIds); // Sort in ascending order

        $this->assertEquals($sortedIds, $ids); // Check if the original ids are in ascending order
    }

    public function search_applies_custom_ordering_by_name()
    {
        Product::factory()->count(10)->create();

        $searchAttributes = ['order_field' => 'name', 'order_by' => 'desc'];
        $paginationAttributes = [];

        $result = $this->productService->search($searchAttributes, $paginationAttributes);

        // Extract names from the items
        $items = $result->items();
        $names = array_column($items, 'name');

        // Create a sorted copy of names
        $sortedNames = $names;
        rsort($sortedNames); // Sort in descending order

        $this->assertEquals($sortedNames, $names); // Check if names are in descending order
    }

    public function test_search_applies_default_ordering_when_order_field_is_invalid()
    {
        Product::factory()->count(10)->create();

        $searchAttributes = ['order_field' => 'invalid_field', 'order_by' => 'asc'];
        $paginationAttributes = [];

        $result = $this->productService->search($searchAttributes, $paginationAttributes);

        // Assert that the results are ordered by 'id' ascending (default)
        $items = $result->items();
        $ids = array_column($items, 'id');

        // Create a sorted copy of ids
        $sortedIds = $ids;
        sort($sortedIds); // Sort in ascending order

        $this->assertEquals($sortedIds, $ids); // Check if ids are in ascending order
    }

    public function test_search_ignores_invalid_order_by_value_and_uses_default()
    {
        Product::factory()->count(10)->create();

        $searchAttributes = ['order_field' => 'name', 'order_by' => 'invalid'];
        $paginationAttributes = [];

        $result = $this->productService->search($searchAttributes, $paginationAttributes);

        // Assert that the results are ordered by 'name' ascending (default)
        $items = $result->items();
        $names = array_column($items, 'name');

        // Create a sorted copy of names
        $sortedNames = $names;
        sort($sortedNames); // Sort in ascending order

        $this->assertEquals($sortedNames, $names); // Check if names are in ascending order
    }

    public function test_view_returns_product_when_product_exists()
    {
        // Create a product using the factory
        $product = Product::factory()->create();

        // Call the view method with the product ID
        $result = $this->productService->view($product->id);

        // Assert that the returned product matches the created product
        $this->assertInstanceOf(Product::class, $result);
        $this->assertEquals($product->id, $result->id);
        $this->assertEquals($product->name, $result->name); // Add other attributes as necessary
    }

    public function test_view_throws_exception_when_product_does_not_exist()
    {
        // Use a product ID that does not exist
        $nonExistentProductId = 9999; // Assuming this ID does not exist

        // Assert that an exception is thrown when trying to view the non-existent product
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Product does not exist");

        // Call the view method
        $this->productService->view($nonExistentProductId);
    }
}
