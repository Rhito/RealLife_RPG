<?php

namespace App\Http\Requests;


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
    abstract protected function tabble(): string;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $tableName = $this->tabble();
        return [
            'ids' => ['required', 'array', 'min:1'],
            // Validate ID must exists in the table and not
            'ids.*' => ['integer', "exists:{$tableName},id,deleted_at,NOT NULL"]
        ];
    }
}
