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
            return response()->json(['message' => 'User not found'], 404);
        }

        if (! hash_equals(sha1($user->getEmailForVerification()), $hash)) {
            return response()->json(['message' => 'Invalid verification link'], 403);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified']);
        }

        $user->markEmailAsVerified();

        return response()->json(['message' => 'Email verified successfully']);
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
