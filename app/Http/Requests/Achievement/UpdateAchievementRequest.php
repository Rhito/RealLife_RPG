<?php

namespace App\Http\Requests\Achievement;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAchievementRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return !!$this->user('admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'condition' => ['sometimes', 'string', 'max:255'],
            'reward_exp' => ['sometimes', 'integer'],
            'reward_coins' => ['sometimes', 'integer'],
            'item_reward_id' => ['nullable'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
