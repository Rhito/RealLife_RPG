<?php

namespace App\Http\Requests\Achievement;

use App\Http\Requests\ApiFormRequest;

class AchievementRequest extends ApiFormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'condition' => ['required', 'string', 'max:255'],
            'reward_exp' => ['sometimes', 'integer'],
            'reward_coins' => ['sometimes', 'integer'],
            'item_reward_id' => ['nullable'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
