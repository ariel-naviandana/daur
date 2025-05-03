<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index() {
        return User::with(['recycleTransactions', 'wallet', 'withdrawals', 'sentChats', 'receivedChats'])->get();
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'role' => 'required|in:user,admin',
            'profile_picture' => 'nullable|string',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        return User::create($validated);
    }

    public function show($id) {
        return User::with(['recycleTransactions', 'wallet', 'withdrawals', 'sentChats', 'receivedChats'])->findOrFail($id);
    }

    public function update(Request $request, $id) {
        $user = User::findOrFail($id);
        $user->update($request->except('password'));
        return $user->load(['recycleTransactions', 'wallet', 'withdrawals', 'sentChats', 'receivedChats']);
    }

    public function destroy($id) {
        User::findOrFail($id)->delete();
        return response()->json(['message' => 'User deleted']);
    }
}

