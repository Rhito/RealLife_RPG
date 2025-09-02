<?php

namespace App\Http\Requests\Task;

use App\Http\Requests\ApiFormRequest;

class TaskRequest extends ApiFormRequest
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
            'user_id' => ['required', 'integer'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'type' => ['required', 'in:daily,once,habit'],
            'difficulty' => ['required', 'in:easy,medium,hard'],
            'repeat_days' => ['nullable', 'array', 'required_if:type,daily', 'exclude_if:type,once'],
            'repeat_days.*' => ['in:mon,tue,wed,thu,fri,sat,sun'],
            'due_date' => ['nullable', 'date', 'required_if:type,once', 'after:now'],
        ];
    }

    /**
     * custome check
     */
    // public function withValidator($validator){
    //     $validator->after(function ($validator) {
    //         $type = $this->input('type');

    //         if($type === 'daily' && empty($this->input('repeat_days'))) {
    //             $validator->error()->add('repeat_days', 'Daily task must have at least one repeat day.');
    //         }

    //     });
    // }
}
