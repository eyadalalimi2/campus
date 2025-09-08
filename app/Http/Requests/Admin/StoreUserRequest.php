<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'student_number' => 'nullable|string|max:255|unique:users,student_number',
            'name'           => 'nullable|string|max:255',
            'email'          => 'required|email|max:255|unique:users,email',
            'phone'          => 'nullable|string|max:20',
            'country_id'     => 'required|exists:countries,id',
            'university_id'  => 'nullable|exists:universities,id',
            'college_id'     => 'nullable|exists:colleges,id',
            'major_id'       => 'nullable|exists:majors,id',
            'level'          => 'nullable|integer|min:1',
            'gender'         => 'nullable|in:male,female',
            'status'         => 'nullable|in:active,suspended,graduated',
            'password'       => 'required|string|min:8|confirmed',
        ];
    }
}
