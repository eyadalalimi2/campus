<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class MajorRequest extends FormRequest
{
    public function authorize(): bool { return auth('admin')->check(); }

    public function rules(): array {
        return [
            'college_id' => 'required|exists:colleges,id',
            'name'       => 'required|string|max:200',
            'code'       => 'nullable|string|max:20',
            'is_active'  => 'nullable|boolean',
        ];
    }
}
