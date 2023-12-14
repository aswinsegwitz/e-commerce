<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'product_id', 'quantity'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class);
    }


    public function addProductToCart($productId, $quantity)
    {
        $cartItem = $this->where(['customer_id' => auth()->id(), 'product_id' => $productId])->first();

        if ($cartItem) {
            // Update the quantity if the product is already in the cart
            $cartItem->update(['quantity' => $cartItem->quantity + $quantity]);
        } else {
            // Add a new item to the cart
            $this->create([
                'customer_id' => auth()->id(),
                'product_id' => $productId,
                'quantity' => $quantity,
            ]);
        }
    }
}
