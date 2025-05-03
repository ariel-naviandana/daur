<?php

namespace App\Http\Controllers;

use App\Models\RecycleTransaction;
use Illuminate\Http\Request;

class RecycleTransactionController extends Controller
{
    public function index() {
        return RecycleTransaction::with(['user', 'items', 'bank'])->get();
    }

    public function store(Request $request) {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'waste_type_id' => 'required|exists:waste_types,id',
            'bank_id' => 'nullable|exists:banks,id',
            'method' => 'required|in:pickup,dropoff',
            'address' => 'nullable|string',
            'status' => 'required|in:waiting,process,cancel,success',
            'weight' => 'required|numeric',
            'total_price' => 'required|numeric',
        ]);

        return RecycleTransaction::create($request->all());
    }

    public function show($id) {
        return RecycleTransaction::with(['user', 'items', 'bank'])->findOrFail($id);
    }

    public function update(Request $request, $id) {
        $trx = RecycleTransaction::findOrFail($id);
        $trx->update($request->all());
        return $trx->load(['user', 'items', 'bank']);
    }

    public function destroy($id) {
        RecycleTransaction::findOrFail($id)->delete();
        return response()->json(['message' => 'Transaction deleted']);
    }
}
