<?php

namespace App\Services;

use App\Services\Contract\SupplierServiceInterface;
use \App\Models\Supplier;

class SupplierService implements SupplierServiceInterface
{
    public function validate($item): bool
    {
        // Validate name
        if (empty($item['name']) || !is_string($item['name']) || strlen($item['name']) > 255) {
            throw new \InvalidArgumentException('Supplier name is required and must be a string with a maximum length of 255 characters.');
        }

        // Validate address
        if (empty($item['address']) || !is_string($item['address']) || strlen($item['address']) > 255) {
            throw new \InvalidArgumentException('Supplier address is required and must be a string with a maximum length of 255 characters.');
        }

        // Validate email
        if (empty($item['email']) || !filter_var($item['email'], FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('A valid email address is required.');
        }

        // Validate phone (optional)
        if (isset($item['phone']) && !is_string($item['phone'])) {
            throw new \InvalidArgumentException('Phone must be a string.');
        } elseif (isset($item['phone']) && strlen($item['phone']) > 50) {
            throw new \InvalidArgumentException('Phone number must not exceed 50 characters.');
        }

        // Validate mobile (optional)
        if (isset($item['mobile']) && !is_string($item['mobile'])) {
            throw new \InvalidArgumentException('Mobile must be a string.');
        } elseif (isset($item['mobile']) && strlen($item['mobile']) > 50) {
            throw new \InvalidArgumentException('Mobile number must not exceed 50 characters.');
        }

        // Validate fax (optional)
        if (isset($item['fax']) && !is_string($item['fax'])) {
            throw new \InvalidArgumentException('Fax must be a string.');
        } elseif (isset($item['fax']) && strlen($item['fax']) > 50) {
            throw new \InvalidArgumentException('Fax number must not exceed 50 characters.');
        }

        return true;
    }

    public function checkIfEmailExists(string $email): bool
    {
        $supplier = Supplier::where('email', $email)->first();
        if ($supplier) {
            throw new \Exception("Supplier Email already exists", 422);
        }
        return true;
    }

    public function checkIfNameExists(string $name): bool
    {
        $supplier = Supplier::where('name', $name)->first();
        if ($supplier) {
            throw new \Exception("Supplier Name already exists", 422);
        }
        return true;
    }


    public function create(array $item): Supplier
    {
        // validate supplier data
        $this->validate($item);

        // check if supplier email exists
        $this->checkIfEmailExists($item['email']);

        // check if supplier name exists
        $this->checkIfNameExists($item['name']);

        // create supplier
        return Supplier::create($item);
    }
}
