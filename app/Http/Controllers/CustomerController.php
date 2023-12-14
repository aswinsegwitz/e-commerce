<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function addToCart($productId, $quantity)
    {
        $cart = new Cart();
        $cart->addProductToCart($productId, $quantity);

        return response()->json(['message' => 'Product added to cart successfully']);
    }
}
