<?php

namespace App\Http\Controllers;

use App\Models\RecycleTransaction;
use App\Models\RecycleTransactionItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecycleTransactionController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $query = RecycleTransaction::with(['user.wallet', 'items.wasteType', 'bank']);

        if ($user->role === 'master_admin')
            return $query->get();
        else if ($user->role === 'bank_admin') {
            if (!$user->bank_id)
                return response()->json(['message' => 'Bank admin must be associated with a bank'], 403);
            return $query->where('bank_id', $user->bank_id)->get();
        } else
            return response()->json(['message' => 'Unauthorized'], 403);
    }

    public function store(Request $request) {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'bank_id' => 'nullable|exists:banks,id',
            'method' => 'required|in:pickup,dropoff',
            'pickup_address' => 'nullable|string',
            'appointment_time' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    $datetime = Carbon::parse($value);
                    $now = Carbon::now();
                    $today = $now->startOfDay();

                    if ($datetime->lt($today)) {
                        $fail('The appointment time cannot be in the past.');
                    }

                    $hour = $datetime->hour;
                    if ($hour < 9 || $hour >= 17) {
                        $fail('The appointment time must be between 09:00 and 17:00.');
                    }

                    if ($datetime->isSameDay($now)) {
                        $minTime = $now->copy()->addHours(2);
                        if ($datetime->lt($minTime)) {
                            $fail('For today, the appointment time must be at least 2 hours from now.');
                        }
                    }
                },
            ],
            'note' => 'nullable|string',
            'status' => 'required|in:waiting,process,cancel,success',
            'total_quantity' => 'nullable|numeric',
            'total_amount' => 'required|numeric',
            'items' => 'required|array',
            'items.*.waste_type_id' => 'required|exists:waste_types,id',
            'items.*.quantity' => 'required|numeric',
            'items.*.sub_total' => 'required|numeric',
            'items.*.image' => 'nullable|string',
        ]);

        $transaction = RecycleTransaction::create($request->except('items'));

        foreach ($request->items as $item) {
            $item['transaction_id'] = $transaction->id;
            RecycleTransactionItem::create($item);
        }

        return $transaction->load(['items.wasteType', 'user', 'bank']);
    }

    public function show($id) {
        return RecycleTransaction::with(['user', 'items.wasteType', 'bank'])->findOrFail($id);
    }

    public function getByUser($userId) {
        return RecycleTransaction::with(['user', 'items.wasteType', 'bank'])
            ->where('user_id', $userId)
            ->get();
    }

    public function update(Request $request, $id) {
        $trx = RecycleTransaction::findOrFail($id);
        $trx->update($request->all());
        return $trx->load(['user', 'items.wasteType', 'bank']);
    }

    public function destroy($id) {
        RecycleTransaction::findOrFail($id)->delete();
        return response()->json(['message' => 'Transaction deleted']);
    }
}
