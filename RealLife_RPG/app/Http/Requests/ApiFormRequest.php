<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class ApiFormRequest extends FormRequest
{
  /**
     * Override the failed validation to return a JSON response.
     */
    public function failedValidation(Validator $validator) : JsonResponse
    {
        throw new HttpResponseException(response()->json([
            'status' => 'Validation failed',
            'message' => 'The given data was invalid.',
            'errors' => $validator->errors(),
        ], 422));
    }
}
