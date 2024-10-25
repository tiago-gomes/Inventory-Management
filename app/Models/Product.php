<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Define the table associated with the model
    protected $table = 'products';

    // Specify the fillable fields
    protected $fillable = [
        'name',
        'description',
        'price',
        'supplier_id',
    ];

    // Optionally, you can define the timestamps
    public $timestamps = true; // This is true by default

    /** Define relationships if needed
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    **/
}
