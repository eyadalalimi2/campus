<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CollegeRequest extends FormRequest
{
    public function authorize(): bool { return auth('admin')->check(); }

    public function rules(): array {
        return [
            'university_id' => 'required|exists:universities,id',
            'name'          => 'required|string|max:200',
            'code'          => 'nullable|string|max:20',
            'is_active'     => 'nullable|boolean',
        ];
    }
}
