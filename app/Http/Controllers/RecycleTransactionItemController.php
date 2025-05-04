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
            'transaction_id' => 'required|exists:recycle_transactions,id',
            'waste_type_id' => 'required|exists:waste_types,id',
            'quantity' => 'required|numeric',
            'sub_total' => 'required|numeric',
        ]);

        return RecycleTransactionItem::create($request->all());
    }

    public function show($id) {
        return RecycleTransactionItem::with(['transaction', 'wasteType'])->findOrFail($id);
    }

    public function getByTransaction($transactionId) {
        return RecycleTransactionItem::with('transaction')
            ->where('transaction_id', $transactionId)
            ->get();
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
