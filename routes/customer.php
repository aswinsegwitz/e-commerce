<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\VendorController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;



Route::group(['middleware' => ['auth', 'customer']], function () {

    // customer dashboard  
    Route::get('/dashboard', function () { 
        $products = Product::all();
        return view('customer.dashboard', compact('products'));
    })->name('customer.dashboard');

    // product cart routes
    Route::group(['prefix' => 'product'], function () {
        Route::post('/add-to-cart/{productId}/{quantity}', [CustomerController::class, 'addToCart'])->name('cart.add-to-cart');
    });

    //cart routes
    Route::group(['prefix' => 'cart'], function () {
        Route::get('/', [CartController::class, 'index'])->name('customer.cart.index');
        Route::put('/update/{cartItemId}', [CartController::class, 'updateQuantity'])->name('customer.cart.update');
        Route::delete('/remove/{cartItemId}', [CartController::class, 'removeFromCart'])->name('customer.cart.remove');
    });

});
