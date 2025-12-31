<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PushSubscriptionController extends ApiController
{
    public function store(Request $request)
    {
        $request->validate([
            'endpoint' => 'required|string',
            'device_name' => 'nullable|string',
        ]);

        $user = Auth::user();

        // Register the token
        $subscription = $user->pushSubsciptions()->updateOrCreate(
            ['endpoint' => $request->endpoint], // Expo Push Token
            [
                'device_name' => $request->device_name,
                'last_used_at' => now(),
            ]
        );

        return $this->success('Device registered for push notifications', ['id' => $subscription->id]);
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'endpoint' => 'required|string',
        ]);

        $user = Auth::user();
        $user->pushSubsciptions()->where('endpoint', $request->endpoint)->delete();

        return $this->success('Device unsubscribed');
    }
}
