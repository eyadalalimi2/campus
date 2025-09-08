<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('admin')->check();
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name'          => is_string($this->name) ? trim($this->name) : $this->name,
            'description'   => is_string($this->description) ? trim($this->description) : $this->description,
            'is_active'     => filter_var($this->is_active, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
            'duration_days' => $this->duration_days ?: null,
        ]);
    }

    public function rules(): array
    {
        $id = $this->route('plan') instanceof \App\Models\Plan
            ? $this->route('plan')->id
            : $this->route('plan');

        return [
            'name'          => ['required','string','max:150', Rule::unique('plans','name')->ignore($id)],
            'duration_days' => ['required','integer','min:1','max:3650'],
            'is_active'     => ['nullable','boolean'],
            'description'   => ['nullable','string','max:1000'],
        ];
    }
}
