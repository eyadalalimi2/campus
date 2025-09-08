<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubscriptionByCodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('admin')->check();
    }

    public function rules(): array
    {
        return [
            'user_id'         => ['required','integer','exists:users,id'],
            // كود من 10 أرقام (يسمح بأصفار بادئة)
            'activation_code' => ['required','regex:/^\d{10}$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required'         => 'الرجاء اختيار الطالب.',
            'user_id.exists'           => 'الطالب غير موجود.',
            'activation_code.required' => 'الرجاء إدخال كود التفعيل.',
            'activation_code.regex'    => 'الكود يجب أن يتكون من 10 أرقام.',
        ];
    }
}
