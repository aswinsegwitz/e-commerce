<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\VendorLoginController;
use App\Http\Controllers\Auth\CustomerLoginController;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['auth', 'admin'], 'prefix' => 'admin'], function () {
    include __DIR__ . '/admin.php';
});


Route::group(['middleware' => ['auth', 'vendor'], 'prefix' => 'vendor'], function () {
    include __DIR__ . '/vendor.php';
});

Route::group(['middleware' => ['auth', 'customer'], 'prefix' => 'customer'], function () {
    include __DIR__ . '/customer.php';
});


Route::middleware(['auth'])->group(function () {
    // Existing routes for the dashboard

    // Product Management Routes
    // Display the form to create a new product
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');

    // Store a newly created product in the database
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');

    // Display the specified product
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

    // Display the form to edit the specified product
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');

    // Update the specified product in the database
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');

    // Delete the specified product from the database
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

    // Display a listing of the products
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
});

// Default routes
Route::get('/', function () {
    return view('welcome');
});


Auth::routes();

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
