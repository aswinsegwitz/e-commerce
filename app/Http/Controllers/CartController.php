<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        // Fetch the current user's cart items with product details
        $cartItems = Auth::user()->cartItems()->with('product')->get();

        // Calculate the total
        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        return view('cart.index', compact('cartItems', 'total'));
    }

    public function updateQuantity(Request $request, $cartItemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = Cart::findOrFail($cartItemId); 
        $cartItem->quantity = $request->input('quantity');
        $cartItem->save();

        return response()->json(['success' => 'Quantity updated successfully']);
    }

    public function removeFromCart($cartItemId)
    {
        $cartItem = Cart::findOrFail($cartItemId);
        $cartItem->delete();

        return response()->json(['success' => 'Item removed from cart successfully']);
    }
}
