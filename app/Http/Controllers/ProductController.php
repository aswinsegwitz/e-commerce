<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        $products = $this->getProductsForCurrentUser();

        return view('products.index', ['products' => $products]);
    }

    public function create()
    {
        return view('products.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'main_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'stock' => 'required|numeric',
            'price' => 'required|numeric'
        ]);

        $user = Auth::user();

        $productData = $request->except('variants');
        $productData['vendor_id'] = $user->id;

        $product = Product::create($productData);

        if ($request->hasFile('main_image')) {
            $mainImage = $request->file('main_image')->store('product_images', 'public');
            $product->update(['main_image' => $mainImage]);
        }

    
        return response()->json(['success' => 'Product created successfully']);
    }


    public function edit(Product $product)
    {
        $this->authorize('edit-product', $product); // Check if the user is authorized to edit this product

        return view('products.edit', compact('product'));
    }


    private function getProductsForCurrentUser()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return Product::all();
        } elseif ($user->isVendor()) {
            return $user->products;
        } else {
            abort(403, 'Unauthorized action.');
        }
    }


    public function update(Request $request, Product $product)
    {
        // Validate the request data
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'main_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'variants' => 'nullable|array',
            'variants.*.size' => 'nullable|string',
            'variants.*.color' => 'nullable|string',
        ]);

        $this->authorize('edit-product', $product); // Check if the user is authorized to edit this product

        // Update the product data
        $productData = $request->except('variants');
        $product->update($productData);

        // Update main image if a new one is provided
        if ($request->hasFile('main_image')) {
            $mainImage = $request->file('main_image')->store('product_images', 'public');
            $product->update(['main_image' => $mainImage]);
        }


        return response()->json(['success' => 'Product updated successfully']);
    }


    public function destroy(Product $product)
    {
        try {
            DB::beginTransaction();

            $this->authorize('delete-product', $product); // Check if the user is authorized to delete this product

            // Log some information
            \Log::info('Deleting product with ID: ' . $product->id);

            // Delete the product 
            $product->delete();

            DB::commit();
            return response()->json(['success' => 'Product deleted successfully']);
        } catch (\Exception $e) {
            DB::rollBack();

            // Log the error
            \Log::error('Failed to delete product. Error: ' . $e->getMessage());

            // Handle the error
            return response()->json(['error' => 'Failed to delete the product'], 500);
        }
    }

}
