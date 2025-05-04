<?php

namespace App\Http\Controllers;

use App\Models\RecycleTransaction;
use App\Models\RecycleTransactionItem;
use Illuminate\Http\Request;

class RecycleTransactionController extends Controller
{
    public function index() {
        return RecycleTransaction::with(['user', 'items', 'bank'])->get();
    }

    public function store(Request $request) {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'bank_id' => 'nullable|exists:banks,id',
            'method' => 'required|in:pickup,dropoff',
            'pickup_address' => 'nullable|string',
            'appointment_time' => 'nullable|date|date_format:Y-m-d H:i:s',
            'note' => 'nullable|string',
            'status' => 'required|in:waiting,process,cancel,success',
            'total_quantity' => 'nullable|numeric',
            'total_amount' => 'required|numeric',
            'items' => 'required|array',
            'items.*.waste_type_id' => 'required|exists:waste_types,id',
            'items.*.quantity' => 'required|numeric',
            'items.*.sub_total' => 'required|numeric',
        ]);

        $transaction = RecycleTransaction::create($request->except('items'));

        foreach ($request->items as $item) {
            $item['transaction_id'] = $transaction->id;
            RecycleTransactionItem::create($item);
        }

        return $transaction->load(['items', 'user', 'bank']);
    }

    public function show($id) {
        return RecycleTransaction::with(['user', 'items', 'bank'])->findOrFail($id);
    }

    public function getByUser($userId) {
        return RecycleTransaction::with('user')
            ->where('user_id', $userId)
            ->get();
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
