<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Friendship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaderboardController extends Controller
{
    public function index()
    {
        // Global Leaderboard: Top 50 by Level DESC, EXP DESC
        $users = User::orderBy('level', 'desc')
            ->orderBy('exp', 'desc')
            ->orderBy('id', 'asc') // Consistent tie-breaking
            ->limit(50)
            ->get(['id', 'name', 'level', 'exp', 'avatar']);

        return response()->json(['data' => $users]);
    }

    public function friends()
    {
        $user = Auth::user();
        
        // Get friend IDs
        $friendIds = Friendship::where(function($q) use ($user) {
                $q->where('user_id', $user->id)->orWhere('friend_id', $user->id);
            })
            ->where('status', 'accepted')
            ->get()
            ->map(function ($friendship) use ($user) {
                return $friendship->user_id === $user->id ? $friendship->friend_id : $friendship->user_id;
            })
            ->toArray();

        // Add self
        $friendIds[] = $user->id;

        // Get users in list
        $users = User::whereIn('id', $friendIds)
            ->orderBy('level', 'desc')
            ->orderBy('exp', 'desc')
            ->orderBy('id', 'asc')
            ->get(['id', 'name', 'level', 'exp', 'avatar']);

        return response()->json(['data' => $users]);
    }
}
