<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Validation\Rules\Password;

class UpdateRequest extends ApiFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $admin = auth('admin')->user();
        return $admin && $admin->role === \App\Enums\AdminRole::SUPER;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['string', 'nullable', 'min:2', 'max:255'],
            'email' => ['string', 'nullable', 'email', 'min:8', 'max:255'],
            'password' => ['string', 'nullable', 'confirmed', Password::default()],
            'role' => ['sometimes', 'in:super,moderator'],
            'not_allowed' => ['boolean'],
        ];
    }
}
