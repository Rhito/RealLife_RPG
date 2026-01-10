<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PasswordResetWebController extends Controller
{
    public function showResetForm(Request $request)
    {
        $token = $request->query('token');
        $email = $request->query('email');
        
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $email
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $status = \Illuminate\Support\Facades\Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => \Illuminate\Support\Facades\Hash::make($password)
                ])->setRememberToken(\Illuminate\Support\Str::random(60));

                $user->save();

                event(new \Illuminate\Auth\Events\PasswordReset($user));
            }
        );

        if ($status == \Illuminate\Support\Facades\Password::PASSWORD_RESET) {
            return view('auth.reset-success');
        }

        return back()->withErrors(['email' => __($status)]);
    }
}
