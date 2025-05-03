<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wallet;

class WalletController extends Controller
{
    public function index() {
        return Wallet::with('user')->get();
    }

    public function store(Request $request) {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'balance' => 'required|numeric',
        ]);

        return Wallet::create($request->all());
    }

    public function show($id) {
        return Wallet::with('user')->findOrFail($id);
    }

    public function update(Request $request, $id) {
        $wallet = Wallet::findOrFail($id);
        $wallet->update($request->all());
        return $wallet->load('user');
    }

    public function destroy($id) {
        Wallet::findOrFail($id)->delete();
        return response()->json(['message' => 'Wallet deleted']);
    }
}
