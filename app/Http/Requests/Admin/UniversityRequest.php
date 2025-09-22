<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UniversityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // ضع التحقق من صلاحيات الإداري لاحقًا إن لزم
    }

    public function rules(): array
    {
        return [
            'name'             => ['required', 'string', 'max:255'],
            'address'          => ['required', 'string', 'max:500'],
            'phone'            => ['nullable', 'string', 'max:50'],
            'logo'             => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],

            // حقول الألوان/الثيم
            'primary_color'    => ['nullable', 'string', 'regex:/^#([A-Fa-f0-9]{3}|[A-Fa-f0-9]{6})$/'],
            'secondary_color'  => ['nullable', 'string', 'regex:/^#([A-Fa-f0-9]{3}|[A-Fa-f0-9]{6})$/'],
            'theme_mode'       => ['nullable', Rule::in(['light','dark'])],

            // Boolean
            'is_active'        => ['nullable','boolean'],
            'use_default_theme'=> ['nullable','boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'     => 'اسم الجامعة مطلوب.',
            'address.required'  => 'العنوان مطلوب.',
            'logo.image'        => 'ملف الشعار يجب أن يكون صورة.',
            'logo.mimes'        => 'صيغة الشعار يجب أن تكون PNG أو JPG أو JPEG أو WEBP.',
            'logo.max'          => 'حجم الشعار يجب ألا يتجاوز 2MB.',

            'primary_color.regex'   => 'لون الواجهة الأساسي يجب أن يكون كود HEX صالح (#RRGGBB).',
            'secondary_color.regex' => 'لون الواجهة الثانوي يجب أن يكون كود HEX صالح (#RRGGBB).',
            'theme_mode.in'         => 'وضع الثيم يجب أن يكون light أو dark فقط.',
        ];
    }
}
