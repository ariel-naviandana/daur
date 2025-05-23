<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index() {
        return User::with(['recycleTransactions', 'wallet', 'sentChats', 'receivedChats'])->get();
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'role' => 'required|in:user,master_admin,bank_admin',
            'profile_picture' => 'nullable|string',
            'bank_id' => 'nullable|exists:banks,id',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        return User::create($validated);
    }

    public function show($id) {
        return User::with(['recycleTransactions', 'wallet', 'sentChats', 'receivedChats'])->findOrFail($id);
    }

    public function update(Request $request, $id) {
        $user = User::findOrFail($id);
        $user->update($request->except('password'));
        return $user->load(['recycleTransactions', 'wallet', 'sentChats', 'receivedChats']);
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $user->update($request->only('name', 'address', 'phone', 'profile_picture'));
        return response()->json(['user' => $user]);
    }

    public function destroy($id) {
        User::findOrFail($id)->delete();
        return response()->json(['message' => 'User deleted']);
    }

    public function getAdmins()
    {
        return User::whereIn('role', ['master_admin', 'bank_admin'])
            ->select('id', 'name', 'role', 'bank_id')
            ->with(['bank' => function ($query) {
                $query->select('id', 'name');
            }])
            ->get();
    }
}

