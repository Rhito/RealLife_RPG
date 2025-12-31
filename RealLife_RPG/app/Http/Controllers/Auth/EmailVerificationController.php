<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class EmailVerificationController extends ApiController
{
    public function sendEmailVerification(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return $this->success('Email already verified');
        }

        $request->user()->sendEmailVerificationNotification();

        return $this->success('Verification link sent!');
    }

    public function submitEmailVerification(Request $request, $id, $hash)
    {
        $user = Auth::loginUsingId($id);

        if (! $user) {
            return response()->view('verification-error', ['message' => 'User not found'], 404);
        }

        if (! hash_equals(sha1($user->getEmailForVerification()), $hash)) {
            return response()->view('verification-error', ['message' => 'Invalid verification link'], 403);
        }

        $alreadyVerified = $user->hasVerifiedEmail();

        if (!$alreadyVerified) {
            $user->markEmailAsVerified();
            event(new \Illuminate\Auth\Events\Verified($user));
        }

        // Check if request is from mobile app (has user agent or specific header)
        $isMobile = $request->header('X-Mobile-App') || 
                    str_contains($request->header('User-Agent', ''), 'Expo');

        if ($isMobile || config('app.mobile_url')) {
            // Redirect to mobile app deep link
            $mobileUrl = config('app.mobile_url', 'realliferpg://');
            return redirect($mobileUrl . 'verification-success?verified=1');
        }

        // Fallback: Show success page
        return response()->view('verification-success', [
            'userName' => $user->name,
            'alreadyVerified' => $alreadyVerified
        ]);
    }

    public function checkEmailVerified (Request $request)
    {
        if($request->user()->hasVerifiedEmail()){
            return response()->json(['verified' => true,]);
        }
        return response()->json([
            'verified' => false,
            'message' => "Your email address is not verified.",
        ], 403);
    }

}
