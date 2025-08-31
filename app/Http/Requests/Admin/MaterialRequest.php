<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MaterialRequest extends FormRequest
{
    public function authorize(): bool { return auth('admin')->check(); }

    public function rules(): array
    {
        return [
            'name'  => 'required|string|max:200',
            'code'  => 'nullable|string|max:50',
            'scope' => ['required', Rule::in(['global','university'])],

            'university_id' => 'nullable|required_if:scope,university|exists:universities,id',
            'college_id'    => 'nullable|exists:colleges,id',
            'major_id'      => 'nullable|exists:majors,id',

            'level' => 'nullable|integer|min:1|max:20',
            'term'  => 'nullable|in:first,second,summer',

            'is_active' => 'nullable|boolean',
        ];
    }
}
