<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Get conversation with a specific friend.
     */
    public function index($friendId)
    {
        $userId = Auth::id();

        // Mark messages as read
        Message::where('sender_id', $friendId)
            ->where('receiver_id', $userId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $messages = Message::where(function ($query) use ($userId, $friendId) {
            $query->where('sender_id', $userId)
                  ->where('receiver_id', $friendId);
        })->orWhere(function ($query) use ($userId, $friendId) {
            $query->where('sender_id', $friendId)
                  ->where('receiver_id', $userId);
        })
        ->orderBy('created_at', 'asc')
        ->get();

        return response()->json($messages);
    }

    /**
     * Send a message to a friend.
     */
    public function store(Request $request, $friendId)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $userId = Auth::id();
        
        // Check if friend exists (optional but good)
        $friend = User::findOrFail($friendId);

        $message = Message::create([
            'sender_id' => $userId,
            'receiver_id' => $friendId,
            'content' => $request->content,
        ]);

        broadcast(new \App\Events\MessageSent($message))->toOthers();

        return response()->json($message, 201);
    }
}
