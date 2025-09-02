<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize(): bool { return auth('admin')->check(); }

    public function rules(): array
    {
        $id = $this->route('user')?->id;

        return [
            'name'  => ['required','string','max:255'],
            'email' => ['required','email','max:255', Rule::unique('users','email')->ignore($id)],
            'password' => [$id ? 'nullable' : 'required','string','min:8'],

            'student_number' => ['nullable','string','max:50', Rule::unique('users','student_number')->ignore($id)],
            'phone'          => ['nullable','string','max:20'],

            'profile_photo'  => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048'],

            'university_id' => ['nullable','exists:universities,id'],
            'college_id'    => ['nullable','exists:colleges,id'],
            'major_id'      => ['nullable','exists:majors,id'],

            'level'    => ['nullable','integer','min:1','max:20'],
            'gender'   => ['nullable', Rule::in(['male','female'])],
            'status'   => ['required', Rule::in(['active','suspended','graduated'])],
        ];
    }
}
