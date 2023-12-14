<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

//use App\Http\Controllers\ProductController;

Route::group(['middleware' => ['auth', 'admin']], function () {
    // Admin dashboard  
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Vendor routes
    Route::group(['prefix' => 'vendor'], function () {
        Route::get('list', [AdminController::class, 'index'])->name('admin.vendor.index');
        Route::get('create', [AdminController::class, 'create'])->name('admin.vendor.create');
        Route::post('store', [AdminController::class, 'store'])->name('admin.vendor.store');
        Route::get('{vendor}/edit', [AdminController::class, 'edit'])->name('admin.vendor.edit');
        Route::put('{vendor}', [AdminController::class, 'update'])->name('admin.vendor.update');
    });

    // Product routes
    //Route::resource('admin.products', ProductController::class);
});
