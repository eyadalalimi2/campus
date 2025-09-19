<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AssetStoreRequest extends FormRequest
{
    public function authorize(): bool { return auth()->user()?->can('manage-assets') ?? false; }

    public function rules(): array
    {
        return [
            'title' => ['required','string','max:190'],
            // بقية حقول الأصل الموجودة لديك...
            'public_major_ids' => ['nullable','array','min:1'], // إن كانت مقيدة
            'public_major_ids.*' => ['integer','exists:public_majors,id'],
            'primary_public_major_id' => ['nullable','integer','in_array:public_major_ids.*'],
            'public_major_priorities' => ['nullable','array'],
            // public_major_priorities[<id>] = رقم
        ];
    }

    public function messages(): array
    {
        return [
            'public_major_ids.min' => 'اختر تخصصًا عامًا واحدًا على الأقل.',
            'public_major_ids.*.exists' => 'تخصص عام غير موجود.',
            'primary_public_major_id.in_array' => 'يجب أن يكون التخصص الرئيسي ضمن القائمة المختارة.',
        ];
    }
}
