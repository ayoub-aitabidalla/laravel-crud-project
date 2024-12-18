<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CategoryController extends Controller
{
  
    public function index()
    {
        $categories = Category::all();
        return response()->json($categories, 200);
    }

    
    public function store(Request $request)
    {
        dd($request->all());
        $request->validate([
            'name' => 'bail|integer|max:10',
        ]);
        dd($request->name);
        if (Category::where('name', $request->get('name'))->exists()) 
        {
            return response()->json(['message' => 'This category name already exists'], 409);
        }
    
        $category = new Category();
        $category->name = $request->get('name');
        $category->save();
        return response()->json(['message' => 'Category created successfully'], 201);
    }
    
   

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'bail|required|string',
        ]);
        try {
            $category = Category::findOrFail($id);
            $category->name = $request->get('name');
            $category->save();
                return response()->json(['message' => 'Category updated successfully'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Category not found'], 404);
        }   
    }


   
    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();
            return response()->json(['message' => 'Category deleted successfully'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Category not found'], 404);
        }
    }

    
    
}
