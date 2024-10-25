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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id(); // This will create an auto-incrementing id (BigInteger)
            $table->string('name'); // Supplier name
            $table->string('address'); // Supplier address
            $table->string('email')->unique(); // Supplier email, ensuring it's unique
            $table->string('phone')->nullable(); // Phone information
            $table->string('mobile')->nullable(); // Mobile information
            $table->string('fax')->nullable(); // Fax information
            $table->timestamps(); // Created_at and updated_at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
