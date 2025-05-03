<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bank;

class BankController extends Controller
{
    public function index() {
        return Bank::with('recycleTransactions')->get();
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string',
            'location' => 'nullable|string',
        ]);

        return Bank::create($request->all());
    }

    public function show($id) {
        return Bank::with('recycleTransactions')->findOrFail($id);
    }

    public function update(Request $request, $id) {
        $bank = Bank::findOrFail($id);
        $bank->update($request->all());
        return $bank->load('recycleTransactions');
    }

    public function destroy($id) {
        Bank::findOrFail($id)->delete();
        return response()->json(['message' => 'Bank deleted']);
    }
}
