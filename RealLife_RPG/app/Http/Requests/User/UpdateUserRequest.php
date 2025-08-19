<?php

namespace App\Http\Requests\User;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends ApiFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() || $this->user("admin");
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:256'],
            'email' => ['string', 'required', 'email', 'min:8', 'max:255'],
            'password' => ['nullable', 'required', 'confirmed', Password::default()],
            'avatar' => ['nullable', 'string'],
        ];
    }
}
