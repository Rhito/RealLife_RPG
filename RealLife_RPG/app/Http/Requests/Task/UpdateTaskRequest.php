<?php

namespace App\Http\Requests\Task;

use App\Http\Requests\ApiFormRequest;

class UpdateTaskRequest extends ApiFormRequest
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
            'user_id' => ['prohibited'],
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'string'],
            'type' => ['sometimes', 'in:daily,once,habit'],
            'difficulty' => ['sometimes', 'in:easy,medium,hard'],
            'repeat_days' => ['sometimes', 'array', 'required_if:type,daily', 'exclude_if:type,once'],
            'repeat_days.*' => ['in:mon,tue,wed,thu,fri,sat,sun'],
            'due_date' => ['sometimes', 'date', 'required_if:type,once', 'after:now']
        ];
    }
}
