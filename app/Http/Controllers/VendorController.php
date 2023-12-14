<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $products = Product::where('vendor_id', $user->id)->where('role', 'vendor')->get();

        return view('products.index', ['products' => $products]);
    }
}
