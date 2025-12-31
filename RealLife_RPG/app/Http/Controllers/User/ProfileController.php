<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ActivityFeed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'avatar' => 'nullable|string', // URL or identifier
        ]);

        $user = Auth::user();
        $updateData = ['name' => $request->name];
        
        if ($request->has('avatar')) {
             $updateData['avatar'] = $request->avatar;
        }

        $user->update($updateData);

        return response()->json(['message' => 'Profile updated', 'data' => $user]);
    }

    public function inventory()
    {
        $user = Auth::user();
        // Return items with pivot data (acquired_at, used)
        $items = $user->items; 
        return response()->json(['data' => $items]);
    }

    public function feed()
    {
        $user = Auth::user();
        // Return activity feed, ordered by newest
        $feed = ActivityFeed::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return response()->json(['data' => $feed]);
    }

    public function stats()
    {
        $user = Auth::user();
        
        // Simple formula: Next Level XP = Level * 100 or something dynamic
        // For now let's assume simple linear scaling: Level * 1000
        $nextLevelExp = $user->level * 1000;

        return response()->json([
            'hp' => $user->hp,
            'max_hp' => $user->max_hp,
            'exp' => $user->exp,
            'level' => $user->level,
            'next_level_exp' => $nextLevelExp,
        ]);
    }

    public function show($id)
    {
        $user = \App\Models\User::findOrFail($id);

        return response()->json([
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'avatar' => $user->avatar,
                'level' => $user->level,
                'exp' => $user->exp,
                'hp' => $user->hp,
                'max_hp' => $user->max_hp,
                'created_at' => $user->created_at,
                // Add any other public stats here
                'stats' => [
                    'tasks_completed' => $user->taskInstances()->where('status', 'completed')->count(),
                    'achievements_unlocked' => $user->achievements()->count(),
                ]
            ]
        ]);
    }
}
