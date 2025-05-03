<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RecycleTransactionItem;

class RecycleTransactionItemController extends Controller
{
    public function index() {
        return RecycleTransactionItem::with(['transaction', 'wasteType'])->get();
    }

    public function store(Request $request) {
        $request->validate([
            'recycle_transaction_id' => 'required|exists:recycle_transactions,id',
            'waste_type_id' => 'required|exists:waste_types,id',
            'weight' => 'required|numeric',
            'total_price' => 'required|numeric',
        ]);

        return RecycleTransactionItem::create($request->all());
    }

    public function show($id) {
        return RecycleTransactionItem::with(['transaction', 'wasteType'])->findOrFail($id);
    }

    public function update(Request $request, $id) {
        $item = RecycleTransactionItem::findOrFail($id);
        $item->update($request->all());
        return $item->load(['transaction', 'wasteType']);
    }

    public function destroy($id) {
        RecycleTransactionItem::findOrFail($id)->delete();
        return response()->json(['message' => 'Recycle Transaction Item deleted']);
    }
}
