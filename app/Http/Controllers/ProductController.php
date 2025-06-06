<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductModel;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function __construct()
    {
        $this->middleware(['permission:product-list|product-create|product-edit|product-delete'], ['only' => ['index', 'show']]);
        $this->middleware(['permission:product-create'], ['only' => ['create', 'store']]);
        $this->middleware(['permission:product-edit'], ['only' => ['edit', 'update']]);
        $this->middleware(['permission:product-delete'], ['only' => ['destroy']]);
    }


    /**
     * Display a listing of the products.
     */
    public function index()
    {
        // Retrieve all products
       $products = Product::orderBy('id', 'desc')->get();

        // Return the view with products
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        // Show the form to create a new product
        return view('products.create');
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
        ]);

        // Create the product
        Product::create([
            'product_name' => $validated['product_name'],
        ]);

        // Redirect to the product index page with success message
        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        // Show the edit form for the product
        return view('products.edit', compact('product'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        // Validate the request data
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
        ]);

        // Update the product
        $product->update([
            'product_name' => $validated['product_name'],
        ]);

        // Redirect to the product index page with success message
        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        // Delete the product
        $product->delete();

        // Redirect to the product index page with success message
        return redirect()->route('products.index')->with('error', 'Product deleted!.');
    }
}
