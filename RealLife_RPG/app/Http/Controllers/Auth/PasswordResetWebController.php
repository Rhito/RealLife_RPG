<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PasswordResetWebController extends Controller
{
    public function showResetRedirect(Request $request)
    {
        $token = $request->query('token');
        $email = $request->query('email');
        
        if (!$token || !$email) {
            return response()->view('verification-error', [
                'message' => 'Invalid password reset link'
            ], 400);
        }
        
        // Create deep link for mobile app
        $deepLink = config('app.mobile_url', 'realliferpg://') . 'reset-password?token=' . $token . '&email=' . urlencode($email);
        
        return view('reset-password-redirect', [
            'deepLink' => $deepLink,
            'email' => $email
        ]);
    }
}
