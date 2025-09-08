<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $id = $this->route('user')?->id ?? $this->route('id');

        return [
            'student_number' => ['nullable','string','max:255', Rule::unique('users','student_number')->ignore($id)],
            'name'           => 'nullable|string|max:255',
            'email'          => ['required','email','max:255', Rule::unique('users','email')->ignore($id)],
            'phone'          => 'nullable|string|max:20',
            'country_id'     => 'required|exists:countries,id',
            'university_id'  => 'nullable|exists:universities,id',
            'college_id'     => 'nullable|exists:colleges,id',
            'major_id'       => 'nullable|exists:majors,id',
            'level'          => 'nullable|integer|min:1',
            'gender'         => 'nullable|in:male,female',
            'status'         => 'nullable|in:active,suspended,graduated',
            'password'       => 'nullable|string|min:8|confirmed',
        ];
    }
}
