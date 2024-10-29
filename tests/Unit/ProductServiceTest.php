<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Services\ProductService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;
use App\Models\Supplier;

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
}
