<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // <-- IMPORT THIS

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::paginate(10); 
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validate the request (including the new image)
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Max 2MB
        ]);

        // 2. Handle the file upload
        if ($request->hasFile('image')) {
            // Store the file in 'storage/app/public/products'
            // and get the path
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path; // Add the path to our validated data
        }

        // 3. Create the product
        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Product added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        // Not used
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        // 1. Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // 2. Handle the file upload (if a new one is provided)
        if ($request->hasFile('image')) {
            // First, delete the old image if it exists
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            
            // Store the new file and get the path
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path; // Add the new path to our data
        }

        // 3. Update the product
        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // 1. Delete all related orders (if any)
        $product->orders()->delete();

        // 2. NEW: Delete the product's image from storage
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        // 3. Delete the product itself
        $product->delete();

        // 4. Redirect
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}