<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UniversityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // ضع التحقق الذي تريده لاحقًا
    }

    public function rules(): array
    {
        return [
            'name'    => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:500'],
            'phone'   => ['nullable', 'string', 'max:50'],
            'logo'    => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'    => 'الاسم مطلوب',
            'address.required' => 'العنوان مطلوب',
            'logo.image'       => 'ملف الشعار يجب أن يكون صورة',
            'logo.mimes'       => 'صيغة الشعار يجب أن تكون PNG أو JPG أو JPEG أو WEBP',
            'logo.max'         => 'حجم الشعار يجب ألا يتجاوز 2MB',
        ];
    }
}
