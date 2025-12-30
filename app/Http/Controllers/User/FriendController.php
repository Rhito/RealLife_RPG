<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Friendship;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FriendController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get accepted friendships where user is sender or receiver
        $friends = Friendship::where(function($q) use ($user) {
                $q->where('user_id', $user->id)->orWhere('friend_id', $user->id);
            })
            ->where('status', 'accepted')
            ->with(['user', 'friend']) // Eager load both
            ->get()
            ->map(function ($friendship) use ($user) {
                // Return the *other* user
                return $friendship->user_id === $user->id ? $friendship->friend : $friendship->user;
            });

        return response()->json(['data' => $friends]);
    }

    public function requests()
    {
        $user = Auth::user();
        
        // Incoming requests: friend_id is me, status is pending
        $requests = Friendship::where('friend_id', $user->id)
            ->where('status', 'pending')
            ->with('user') // The sender
            ->get();

        return response()->json(['data' => $requests]);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        if (!$query) return response()->json(['data' => []]);

        $user = Auth::user();

        // Find users matching name/email, excluding self
        $users = User::where('id', '!=', $user->id)
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%");
            })
            ->get();

        // Filter out existing friends/requests (optional, but good UX)
        // For simplicity, just return list with a "status" field check could be done in frontend or here.
        // Let's do a simple returning of users. Frontend checks if they are already friends.
        
        return response()->json(['data' => $users]);
    }

    public function store(Request $request)
    {
        $request->validate(['friend_id' => 'required|exists:users,id']);
        $user = Auth::user();
        $friendId = $request->input('friend_id');

        if ($user->id == $friendId) {
            return response()->json(['message' => 'Cannot add yourself'], 400);
        }

        // Check if exists
        $exists = Friendship::where(function($q) use ($user, $friendId) {
            $q->where('user_id', $user->id)->where('friend_id', $friendId);
        })->orWhere(function($q) use ($user, $friendId) {
             $q->where('user_id', $friendId)->where('friend_id', $user->id);
        })->first();

        if ($exists) {
            return response()->json(['message' => 'Friendship request already exists or you are already friends'], 400);
        }

        Friendship::create([
            'user_id' => $user->id,
            'friend_id' => $friendId,
            'status' => 'pending',
        ]);

        return response()->json(['message' => 'Request sent']);
    }

    public function update(string $id)
    {
        $user = Auth::user();
        // Accept request. ID is the Friendship ID.
        // Ensure I am the recipient (friend_id)
        $friendship = Friendship::where('id', $id)
            ->where('friend_id', $user->id)
            ->where('status', 'pending')
            ->firstOrFail();

        $friendship->update([
            'status' => 'accepted',
        ]);

        return response()->json(['message' => 'Friend request accepted']);
    }

    public function destroy(string $id)
    {
        $user = Auth::user();
        // ID is Friendship ID? Or User ID?
        // Let's assume Friendship ID for consistency with typical REST resources, 
        // OR we can make it search for relationship.
        // Let's use Friendship ID passed from frontend list.
        
        $friendship = Friendship::where('id', $id)
            ->where(function($q) use ($user) {
                $q->where('user_id', $user->id)->orWhere('friend_id', $user->id);
            })
            ->firstOrFail();

        $friendship->delete();

        return response()->json(['message' => 'Removed friend']);
    }
}
