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
            // Create User
            $newUser = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
            ]);

            // Send an email to verifi the user
            $newUser->sendEmailVerificationNotification();

            // Create token
            $token = $newUser->createToken('API Token of ' . $newUser->email, ['*'], now()->addWeek())->plainTextToken;

            // Commit databsae change
            DB::commit();
            return $this->success('User created successfuly', ['user' => $newUser, 'token' => $token]);
        } catch (\Exception $error) {
            DB::rollBack();
            return $this->error('Failed register', ['error' => $error->getMessage()]);
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
