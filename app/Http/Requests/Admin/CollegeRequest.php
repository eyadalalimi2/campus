<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CollegeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // ضع صلاحياتك إن لزم
    }

    public function rules(): array
    {
        return [
            'university_id' => ['required', 'exists:universities,id'],
            'name'          => ['required', 'string', 'max:255'],
            'is_active'     => ['nullable', 'boolean'],
            // لا يوجد code هنا
        ];
    }

    public function messages(): array
    {
        return [
            'university_id.required' => 'يرجى اختيار الجامعة.',
            'university_id.exists'   => 'الجامعة المحددة غير موجودة.',
            'name.required'          => 'اسم الكلية مطلوب.',
        ];
    }
}
