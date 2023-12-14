<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    
    protected $fillable = ['title', 'description', 'main_image', 'vendor_id', 'stock', 'price'];
      /**
     * Define a relationship to the vendor (user) of the product.
     */
    public function vendor()
    {
        return $this->belongsTo(User::class);
    }
}
