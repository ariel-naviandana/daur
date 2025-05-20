<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index() {
        $userId = Auth::id();
        return Chat::with(['sender', 'receiver'])
            ->where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->get();
    }

    public function store(Request $request) {
        $request->validate([
            'sender_id' => 'required|exists:users,id',
            'receiver_id' => 'required|exists:users,id',
            'message' => 'nullable|string',
            'image' => 'nullable|string',
        ]);

        return Chat::create($request->all());
    }

    public function show($id) {
        return Chat::with(['sender', 'receiver'])->findOrFail($id);
    }

    public function update(Request $request, $id) {
        $chat = Chat::findOrFail($id);
        $chat->update($request->all());
        return $chat->load(['sender', 'receiver']);
    }

    public function destroy($id) {
        Chat::findOrFail($id)->delete();
        return response()->json(['message' => 'Chat deleted']);
    }
}
