<?php

namespace App\Http\Controllers;

use App\Models\Withdrawal;
use Illuminate\Http\Request;

class WithdrawalController extends Controller
{
    public function index() {
        return Withdrawal::with('user')->get();
    }

    public function store(Request $request) {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric',
            'method' => 'required|in:bank,e-wallet',
            'account_info' => 'required|string',
            'status' => 'required|in:waiting,process,cancel,success',
        ]);

        return Withdrawal::create($request->all());
    }

    public function show($id) {
        return Withdrawal::with('user')->findOrFail($id);
    }

    public function update(Request $request, $id) {
        $wd = Withdrawal::findOrFail($id);
        $wd->update($request->all());
        return $wd->load('user');
    }

    public function destroy($id) {
        Withdrawal::findOrFail($id)->delete();
        return response()->json(['message' => 'Withdrawal deleted']);
    }
}
