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
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'min:2', 'max:256'],
            'email' => ['string', 'prohibited', 'email', 'min:8', 'max:255'],
            'password' => ['sometimes', 'required', 'confirmed', Password::default()],
            'avatar' => [
                'sometimes',
                'file',          // phải là file
                'image',         // là ảnh (jpeg, png, bmp, gif, svg, webp)
                'mimes:jpeg,png,jpg,gif,webp', // cụ thể format cho chắc
                'max:2048',      // dung lượng tối đa 2MB
                'dimensions:min_width=100,min_height=100,max_width=3000,max_height=3000'
            ],
        ];
    }
}
