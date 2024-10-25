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
        Schema::create('inventory', function (Blueprint $table) {
            $table->id(); // Automatically creates an unsigned BIGINT primary key 'id'
            $table->foreignId('product_id');
            $table->integer('quantity'); // Current quantity in stock
            $table->integer('threshold'); // Minimum stock threshold
            $table->timestamps(); // Adds created_at and updated_at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory');
    }
};
