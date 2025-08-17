<?php

namespace App\Http\Controllers\DashBoard\Admin;

use App\Http\Controllers\ApiController;
use App\Http\Requests\LoginRequest;
use App\Models\Admin;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends ApiController
{
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $admin = Admin::where('email', $request->email)->first();

            if (!$admin || ! Hash::check($request->password, $admin->password)) {
                return $this->error('Credentials do not match', null, 401);
            }

            if ($admin->not_allowed) {
                return $this->error('This account was locked', null, 403);
            }

            $token = $admin->createToken('API Token of ' . $admin->email, ['*'], now()->addWeek())->plainTextToken;

            return $this->success('Login successful', ['admin' => $admin, 'token' => $token]);
        } catch (\Exception $error) {
            return $this->error('Failed register', ['error' => $error->getMessage()]);
        }
    }



    public function logout(Request $request)
    {
        try {
            /** @var PersonalAccessToken|null $token */
            $token = $request->user('admin')?->currentAccessToken();
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
