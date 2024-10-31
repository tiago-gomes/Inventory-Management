<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Services\SupplierService;
use Tests\TestCase;
use App\Models\Supplier;

class SupplierServiceTest extends TestCase
{

    use WithFaker, RefreshDatabase;

    protected $supplierService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->supplierService = new SupplierService();
    }

    public function test_validate_success()
    {
        $item = [
            'name' => 'Valid Supplier',
            'address' => '123 Valid St.',
            'email' => 'supplier@example.com',
            'phone' => '123456789',
            'mobile' => '987654321',
            'fax' => '555555555',
        ];

        $result = $this->supplierService->validate($item);

        $this->assertTrue($result);
    }


    public function test_validate_missing_name()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Supplier name is required and must be a string with a maximum length of 255 characters.');

        $item = [
            'name' => '',
            'address' => '123 Valid St.',
            'email' => 'supplier@example.com',
        ];

        $this->supplierService->validate($item);
    }

    public function test_validate_missing_address()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Supplier address is required and must be a string with a maximum length of 255 characters.');

        $item = [
            'name' => 'Valid Supplier',
            'address' => '',
            'email' => 'supplier@example.com',
        ];

        $this->supplierService->validate($item);
    }

    public function test_validate_invalid_email()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('A valid email address is required.');

        $item = [
            'name' => 'Valid Supplier',
            'address' => '123 Valid St.',
            'email' => 'invalid-email',
        ];

        $this->supplierService->validate($item);
    }

    public function test_validate_too_long_name()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Supplier name is required and must be a string with a maximum length of 255 characters.');

        $item = [
            'name' => str_repeat('A', 256), // Name longer than 255 characters
            'address' => '123 Valid St.',
            'email' => 'supplier@example.com',
        ];

        $this->supplierService->validate($item);
    }

    public function test_validate_too_long_address()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Supplier address is required and must be a string with a maximum length of 255 characters.');

        $item = [
            'name' => 'Valid Supplier',
            'address' => str_repeat('A', 256), // Address longer than 255 characters
            'email' => 'supplier@example.com',
        ];

        $this->supplierService->validate($item);
    }

    public function test_validate_invalid_phone()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Phone must be a string.');

        $item = [
            'name' => 'Valid Supplier',
            'address' => '123 Valid St.',
            'email' => 'supplier@example.com',
            'phone' => 123456789, // Phone should be a string
        ];

        $this->supplierService->validate($item);
    }

    public function test_validate_too_long_phone()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Phone number must not exceed 50 characters.');

        $item = [
            'name' => 'Valid Supplier',
            'address' => '123 Valid St.',
            'email' => 'supplier@example.com',
            'phone' => str_repeat('1', 51), // Phone longer than 50 characters
        ];

        $this->supplierService->validate($item);
    }

    public function test_validate_invalid_mobile()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Mobile must be a string.');

        $item = [
            'name' => 'Valid Supplier',
            'address' => '123 Valid St.',
            'email' => 'supplier@example.com',
            'mobile' => 987654321, // Mobile should be a string
        ];

        $this->supplierService->validate($item);
    }

    public function test_validate_too_long_mobile()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Mobile number must not exceed 50 characters.');

        $item = [
            'name' => 'Valid Supplier',
            'address' => '123 Valid St.',
            'email' => 'supplier@example.com',
            'mobile' => str_repeat('1', 51), // Mobile longer than 50 characters
        ];

        $this->supplierService->validate($item);
    }

    public function test_validate_invalid_fax()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Fax must be a string.');

        $item = [
            'name' => 'Valid Supplier',
            'address' => '123 Valid St.',
            'email' => 'supplier@example.com',
            'fax' => 555555555, // Fax should be a string
        ];

        $this->supplierService->validate($item);
    }

    public function test_validate_too_long_fax()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Fax number must not exceed 50 characters.');

        $item = [
            'name' => 'Valid Supplier',
            'address' => '123 Valid St.',
            'email' => 'supplier@example.com',
            'fax' => str_repeat('1', 51), // Fax longer than 50 characters
        ];

        $this->supplierService->validate($item);
    }

    public function test_create_supplier_success()
    {
        // Create a valid supplier item using the factory
        $item = Supplier::factory()->make()->toArray();

        // Call the create method
        $supplier = $this->supplierService->create($item);

        // Assert that the supplier was created successfully
        $this->assertDatabaseHas('suppliers', [
            'id' => $supplier->id,
            'name' => $item['name'],
            'address' => $item['address'],
            'email' => $item['email'],
        ]);
    }

    public function test_create_supplier_with_existing_email()
    {
        // Create an existing supplier
        $existingSupplier = Supplier::factory()->create();

        // Attempt to create a new supplier with the same email
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Supplier Email already exists');

        $item = Supplier::factory()->make(['email' => $existingSupplier->email])->toArray();
        $this->supplierService->create($item);
    }

    public function test_create_supplier_with_existing_name()
    {
        // Create an existing supplier
        $existingSupplier = Supplier::factory()->create();

        // Attempt to create a new supplier with the same name
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Supplier Name already exists');

        $item = Supplier::factory()->make(['name' => $existingSupplier->name])->toArray();
        $this->supplierService->create($item);
    }

    public function test_create_supplier_missing_name()
    {
        // Attempt to create a supplier with a missing name
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Supplier name is required and must be a string with a maximum length of 255 characters.');

        $item = Supplier::factory()->make(['name' => ''])->toArray();
        $this->supplierService->create($item);
    }

    public function test_create_supplier_missing_address()
    {
        // Attempt to create a supplier with a missing address
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Supplier address is required and must be a string with a maximum length of 255 characters.');

        $item = Supplier::factory()->make(['address' => ''])->toArray();
        $this->supplierService->create($item);
    }

    public function test_create_supplier_invalid_email()
    {
        // Attempt to create a supplier with an invalid email
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('A valid email address is required.');

        $item = Supplier::factory()->make(['email' => 'invalid-email'])->toArray();
        $this->supplierService->create($item);
    }

    public function test_create_supplier_too_long_name()
    {
        // Attempt to create a supplier with a too long name
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Supplier name is required and must be a string with a maximum length of 255 characters.');

        $item = Supplier::factory()->make(['name' => str_repeat('A', 256)])->toArray();
        $this->supplierService->create($item);
    }

    public function test_create_supplier_too_long_address()
    {
        // Attempt to create a supplier with a too long address
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Supplier address is required and must be a string with a maximum length of 255 characters.');

        $item = Supplier::factory()->make(['address' => str_repeat('A', 256)])->toArray();
        $this->supplierService->create($item);
    }

    public function test_create_supplier_invalid_phone()
    {
        // Attempt to create a supplier with an invalid phone
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Phone must be a string.');

        $item = Supplier::factory()->make(['phone' => 123456789])->toArray();
        $this->supplierService->create($item);
    }

    public function test_create_supplier_too_long_phone()
    {
        // Attempt to create a supplier with a too long phone
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Phone number must not exceed 50 characters.');

        $item = Supplier::factory()->make(['phone' => str_repeat('1', 51)])->toArray();
        $this->supplierService->create($item);
    }

    public function test_create_supplier_invalid_mobile()
    {
        // Attempt to create a supplier with an invalid mobile
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Mobile must be a string.');

        $item = Supplier::factory()->make(['mobile' => 987654321])->toArray();
        $this->supplierService->create($item);
    }

    public function test_create_supplier_too_long_mobile()
    {
        // Attempt to create a supplier with a too long mobile
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Mobile number must not exceed 50 characters.');

        $item = Supplier::factory()->make(['mobile' => str_repeat('1', 51)])->toArray();
        $this->supplierService->create($item);
    }

    public function test_create_supplier_invalid_fax()
    {
        // Attempt to create a supplier with an invalid fax
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Fax must be a string.');

        $item = Supplier::factory()->make(['fax' => 555555555])->toArray();
        $this->supplierService->create($item);
    }

    public function test_create_supplier_too_long_fax()
    {
        // Attempt to create a supplier with a too long fax
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Fax number must not exceed 50 characters.');

        $item = Supplier::factory()->make(['fax' => str_repeat('1', 51)])->toArray();
        $this->supplierService->create($item);
    }
}
