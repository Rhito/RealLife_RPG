<?php

namespace App\Http\Requests\User;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends ApiFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['string', 'required', 'min:2', 'max:255'],
            'email' => ['string', 'required', 'email', 'min:8', 'max:255', 'unique:users,email'],
            'password' => ['string', 'required', 'confirmed', Password::default()],
            //'avatar' => ['nullable', 'string', 'max:2048'],
        ];
    }
}
