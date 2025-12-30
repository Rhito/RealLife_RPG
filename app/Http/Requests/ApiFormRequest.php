<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class ApiFormRequest extends FormRequest
{
    public function wantsJson(): bool
    {
        return true; // always return  response JSON
    }

    /**
     * Overide user() to support many guard
     */
    public function user($guard = null): ?Authenticatable
    {
        // Nếu truyền guard thì dùng guard đó
        if ($guard) {
            return auth($guard)->user();
        }

        // if param null take form default
        $user = parent::user();
        if ($user) {
            return $user;
        }

        // fallback: try guard admin
        return auth('admin')->user();
    }
    /**
     * Override the failed validation to return a JSON response.
     */
    public function failedValidation(Validator $validator): JsonResponse
    {
        throw new HttpResponseException(response()->json([
            'status' => 'Validation failed',
            'message' => 'The given data was invalid.',
            'errors' => $validator->errors(),
        ], 422));
    }
    /**
     * Override the failed authorization to return a JSON response.
     */
    protected function failedAuthorization()
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => "You don't have permission to perform this action."
            ], 403)
        );
    }
}
