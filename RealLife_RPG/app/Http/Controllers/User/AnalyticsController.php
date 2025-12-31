<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\TaskInstance;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. History (Last 7 days)
        $endDate = Carbon::now();
        $startDate = Carbon::now()->subDays(6);

        $dailyCounts = TaskInstance::where('user_id', $user->id)
            ->where('status', 'completed')
            ->whereDate('completed_at', '>=', $startDate->format('Y-m-d'))
            ->whereDate('completed_at', '<=', $endDate->format('Y-m-d'))
            ->selectRaw('DATE(completed_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->pluck('count', 'date'); // ['2023-10-25' => 5]

        $history = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $count = $dailyCounts->get($date) ?? 0;
            
            $history[] = [
                'date' => $date, 
                'day' => Carbon::parse($date)->format('D'),
                'count' => $count
            ];
        }

        // 2. Streak
        // Get all unique completed dates descending
        $dates = TaskInstance::where('user_id', $user->id)
            ->where('status', 'completed')
            ->orderBy('completed_at', 'desc')
            ->get()
            ->pluck('completed_at')
            ->map(fn($d) => $d->format('Y-m-d'))
            ->unique()
            ->values();

        $streak = 0;
        $today = Carbon::now()->format('Y-m-d');
        $yesterday = Carbon::now()->subDay()->format('Y-m-d');

        if ($dates->isNotEmpty()) {
            $latest = $dates->first();
            // Streak is valid if last completion was today or yesterday
            if ($latest === $today || $latest === $yesterday) {
                $currentCheck = ($latest === $today) ? $today : $yesterday;
                
                foreach ($dates as $date) {
                    if ($date === $currentCheck) {
                        $streak++;
                        $currentCheck = Carbon::parse($currentCheck)->subDay()->format('Y-m-d');
                    } else {
                        break;
                    }
                }
            }
        }

        // 3. Rank
        $rank = User::where('level', '>', $user->level)
            ->orWhere(function ($query) use ($user) {
                $query->where('level', $user->level)
                    ->where('exp', '>', $user->exp);
            })
            ->count() + 1;

        return response()->json([
            'streak' => $streak,
            'history' => $history,
            'rank' => $rank,
            'coins' => $user->coins, // Ensure latest coins are returned
        ]);
    }
}
