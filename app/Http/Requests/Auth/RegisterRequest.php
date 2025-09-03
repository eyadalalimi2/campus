<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array {
        return [
            'name'  => ['required','string','max:255'],
            'email' => ['required','email','unique:users,email'],
            'phone' => ['nullable','string','max:20'],
            'country' => ['nullable','string','max:100'],
            'password' => ['required','confirmed','min:8'],
            // إن رغبت ربط الجامعة/الكلية/التخصص عند التسجيل:
            'university_id' => ['nullable','exists:universities,id'],
            'college_id'    => ['nullable','exists:colleges,id'],
            'major_id'      => ['nullable','exists:majors,id'],
        ];
    }
}
