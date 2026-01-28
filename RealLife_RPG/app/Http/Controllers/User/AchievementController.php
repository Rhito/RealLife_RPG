<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AchievementController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Fetch all achievements
        $achievements = Achievement::with(['users' => function ($query) use ($user) {
                // Get current user's unlock status
                $query->where('user_id', $user->id);
            }])
            ->get();

            // Transform collection to include 'is_unlocked' flag
            $data = $achievements->map(function ($achievement) {
            $userAchievement = $achievement->users->first();
            
            return [
                'id' => $achievement->id,
                'name' => $achievement->name,
                'description' => $achievement->description,
                'condition' => $achievement->condition,
                'reward_exp' => $achievement->reward_exp,
                'reward_coins' => $achievement->reward_coins,
                'icon' => $achievement->icon ?? null, // Assuming icon might exist or be added
                'is_unlocked' => $userAchievement ? true : false,
                'unlocked_at' => $userAchievement ? $userAchievement->pivot->unlocked_at : null,
            ];
        });

        return response()->json(['data' => $data]);
    }
}
