<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

abstract class BulkRestoreRequest extends ApiFormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() || $this->user("admin");
    }

    /**
     * Child class must be required this field
     */
    abstract protected function table(): string;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $tableName = $this->table();
        return [
            'ids' => ['required', 'array', 'min:1'],
            // Validate ID must exists in the table and not
            'ids.*' => ['integer', Rule::exists($tableName, 'id')->whereNotNull('deleted_at')]
        ];
    }
}
