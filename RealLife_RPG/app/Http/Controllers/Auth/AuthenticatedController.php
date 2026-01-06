<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\ApiController;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\PersonalAccessToken;

class AuthenticatedController extends ApiController
{
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        // Attempt to authenticate the user
        if (!Auth::attempt($credentials))
            return $this->error('Credentials do not match', null, 401);

        // Retrive the user
        $user = Auth::user();

        // Generate API token access
        $token = $user->createToken('API Token of ' . $user->email, ['*'], now()->addWeek())->plainTextToken;

        // Return success respose
        return $this->success('Login successful', ['user' => $user, 'token' => $token]);
    }

    public function register(StoreUserRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            // Create User (Starter Kit: 500 Coins/XP, 50 HP)
            $newUser = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'coins' => 500,
                'exp' => 500,
                'level' => 1,
                'hp' => 50,
                'max_hp' => 100,
            ]);

            // Send Welcome Email
            try {
                \Illuminate\Support\Facades\Mail::to($newUser)->send(new \App\Mail\WelcomeEmail($newUser));
            } catch (\Exception $e) {
                // Log error but don't block registration
                // \Illuminate\Support\Facades\Log::error('Welcome email failed: ' . $e->getMessage());
            }

            // Create token
            $token = $newUser->createToken('API Token of ' . $newUser->email, ['*'], now()->addWeek())->plainTextToken;

            // Commit databsae change
            DB::commit();
            return $this->success('User created successfuly', ['user' => $newUser, 'token' => $token]);
        } catch (\Exception $error) {
            DB::rollBack();
            // Return actual error for debugging
            return $this->error('Failed register: ' . $error->getMessage());
        }
    }

    public function logout(Request $request)
    {
        try {
            /** @var \App\Models\User|null $user */
            $user = Auth::user();

            /** @var PersonalAccessToken|null $token */
            $token = $user?->currentAccessToken();
            if (! $token) {
                throw new \Exception("No active token found");
            }
            $token?->delete();

            return $this->success('Logout successful');
        } catch (\Exception $error) {
            return $this->error("Logout failed", ['error' => $error->getMessage()]);
        }
    }
}
