<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // This will create a BigInteger id column as primary key.
            $table->foreignId('supplier_id');
            $table->string('name'); // String column for the product name.
            $table->text('description'); // Text column for the product description.
            $table->decimal('price', 10, 2); // Decimal column for the product price.
            $table->timestamps(); // This will create created_at and updated_at timestamp columns.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
