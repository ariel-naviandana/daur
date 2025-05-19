<?php

namespace App\Http\Controllers;

use App\Models\WalletTransaction;
use Illuminate\Http\Request;

class WalletTransactionController extends Controller
{
    public function index() {
        return WalletTransaction::with('wallet')->get();
    }

    public function store(Request $request) {
        $request->validate([
            'wallet_id' => 'required|exists:wallets,id',
            'amount' => 'required|numeric',
            'type' => 'required|in:deposit,withdrawal',
            'method' => 'nullable|in:bca,bni,bri,mandiri,bsi,gopay,dana,ovo',
            'account_info' => 'nullable|string',
            'status' => 'required|in:waiting,approved,rejected',
        ]);

        return WalletTransaction::create($request->all());
    }

    public function show($id) {
        return WalletTransaction::with('wallet')->findOrFail($id);
    }

    public function update(Request $request, $id) {
        $wd = WalletTransaction::findOrFail($id);
        $wd->update($request->all());
        return $wd->load('wallet');
    }

    public function destroy($id) {
        WalletTransaction::findOrFail($id)->delete();
        return response()->json(['message' => 'Wallet Transaction deleted']);
    }
}
