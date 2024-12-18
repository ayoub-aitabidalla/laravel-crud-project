<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Support\Facades\Storage;

use App\Http\Requests\StoreProduct;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductController extends Controller
{

    public function show($id)
    {
        try {
            $product = Product::findOrFail($id);
            return response()->json(['data' => $product]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'product not found'], 404);
        }
    }

    public function index()
    {
        $products = Product::with('category')->get();

        return response()->json([
            'message' => 'Products retrieved successfully!',
            'products' => $products,
        ], 200);
    }


    public function store(StoreProduct $request)
    {

        $product = new Product();
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->category_id = $request->category_id;

        // Handle image upload
        if ($request->hasFile('image')) {
            $filePath = $request->file('image')->store('images', 'public');
            $product->image = $filePath;
        }

        $product->save();
        return response()->json(['message' => 'product created successfully'], 201);
    }


    public function update(StoreProduct $request, $id)
    {
        $product = Product::findOrFail($id);

        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->category_id = $request->category_id;

        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($product->image && Storage::exists('public/' . $product->image)) {
                Storage::delete('public/' . $product->image);
            }

            $filePath = $request->file('image')->store('images', 'public');
            $product->image = $filePath;
        }
        $product->save();

        return response()->json(['message' => 'Product updated successfully'], 200);
    }


    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->delete();
            return response()->json(['message' => 'product deleted successfully'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'product not found'], 404);
        }
    }

    
}
