<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'inventory';

    protected $fillable = [
        'product_id',
        'quantity',
        'threshold',
    ];

    // Define relationship to Product model
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
