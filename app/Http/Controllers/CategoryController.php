<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index() {
        return Category::with('wasteTypes')->get();
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string',
            'image' => 'nullable|string',
        ]);

        return Category::create($request->all());
    }

    public function show($id) {
        return Category::with('wasteTypes')->findOrFail($id);
    }

    public function update(Request $request, $id) {
        $category = Category::findOrFail($id);
        $category->update($request->all());
        return $category->load('wasteTypes');
    }

    public function destroy($id) {
        Category::findOrFail($id)->delete();
        return response()->json(['message' => 'Category deleted']);
    }
}
