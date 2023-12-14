<?php

use App\Http\Controllers\VendorController;
use Illuminate\Support\Facades\Route;



Route::group(['middleware' => ['auth', 'vendor']], function () {

    // vendor dashboard  
    Route::get('/dashboard', function () {
        return view('vendor.dashboard');
    })->name('vendor.dashboard');

});

