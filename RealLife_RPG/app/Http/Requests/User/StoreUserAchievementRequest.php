<?php

namespace App\Http\Requests\User;

use App\Http\Requests\ApiFormRequest;

class StoreUserAchievementRequest extends ApiFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() || $this->user('admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer'],
            'achievement_id' => ['required', 'integer'],
            'unlocked_at' => ['required', 'date'],
        ];
    }
}
