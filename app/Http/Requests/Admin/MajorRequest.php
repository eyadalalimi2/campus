<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class MajorRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'college_id' => ['required','exists:colleges,id'],
            'name'       => ['required','string','max:255'],
            'is_active'  => ['nullable','boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'college_id.required' => 'يرجى اختيار الكلية.',
            'college_id.exists'   => 'الكلية المحددة غير موجودة.',
            'name.required'       => 'اسم التخصص مطلوب.',
        ];
    }
}
