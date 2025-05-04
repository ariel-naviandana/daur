<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WasteType;

class WasteTypeController extends Controller
{
    public function index() {
        return WasteType::with('category')->get();
    }

    public function store(Request $request) {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string',
            'unit' => 'required|string',
            'price_per_unit' => 'required|numeric',
            'image' => 'nullable|string',
        ]);

        return WasteType::create($request->all());
    }

    public function show($id) {
        return WasteType::with('category')->findOrFail($id);
    }

    public function update(Request $request, $id) {
        $waste = WasteType::findOrFail($id);
        $waste->update($request->all());
        return $waste->load('category');
    }

    public function destroy($id) {
        WasteType::findOrFail($id)->delete();
        return response()->json(['message' => 'Waste Type deleted']);
    }
}
