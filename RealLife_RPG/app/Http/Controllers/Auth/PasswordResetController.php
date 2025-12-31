<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\ApiController;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PasswordResetController extends ApiController
{
    /**
     * Handle an incoming password reset link request.
     */
    public function forgotPassword(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Send the password reset link
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            throw ValidationException::withMessages([
                'email' => [__($status)],
            ]);
        }

        return $this->success(__($status));
    }

    /**
     * Handle an incoming new password request.
     * Returns a token for immediate login after password reset.
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        // Reset the user's password
        $user = null;
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($resetUser) use ($request, &$user) {
                $resetUser->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($resetUser));
                
                // Store user reference for token generation
                $user = $resetUser;
            }
        );

        if ($status != Password::PASSWORD_RESET) {
            throw ValidationException::withMessages([
                'email' => [__($status)],
            ]);
        }

        // Generate token for immediate login (for native app convenience)
        $token = $user->createToken('password-reset-login', ['*'], now()->addWeek())->plainTextToken;

        return $this->success('Password reset successful', [
            'user' => $user,
            'token' => $token,
        ]);
    }

    /**
     * Verify if a password reset token is valid.
     * Useful for native apps to check token validity before showing reset form.
     */
    public function verifyResetToken(Request $request): JsonResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
        ]);

        $user = Password::getUser($request->only('email'));

        if (!$user) {
            return $this->error('User not found', null, 404);
        }

        $tokenValid = Password::tokenExists($user, $request->token);

        if (!$tokenValid) {
            return $this->error('Invalid or expired reset token', null, 400);
        }

        return $this->success('Token is valid', [
            'valid' => true,
            'email' => $user->email,
        ]);
    }
}
