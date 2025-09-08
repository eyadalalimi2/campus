<?php

namespace App\Http\Requests\Api\V1\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * إيقاف التحقق عند أول خطأ لتقليل زمن الاستجابة
     */
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    /**
     * تجهيز البيانات قبل التحقق
     */
    protected function prepareForValidation(): void
    {
        $email = $this->input('email');
        $device = $this->input('login_device');

        $this->merge([
            'email'        => $email !== null ? mb_strtolower(trim($email)) : $email,
            'login_device' => $device !== null ? trim($device) : $device,
        ]);
    }

    public function rules(): array
    {
        return [
            'email'        => ['required', 'string', 'email', 'max:255'],
            'password'     => ['required', 'string', 'min:6', 'max:100'],
            'login_device' => ['required', 'string', 'max:60'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required'        => 'البريد الإلكتروني مطلوب.',
            'email.email'           => 'صيغة البريد الإلكتروني غير صحيحة.',
            'email.max'             => 'البريد الإلكتروني يتجاوز الحد المسموح 255 حرفًا.',
            'password.required'     => 'كلمة المرور مطلوبة.',
            'password.min'          => 'كلمة المرور يجب ألا تقل عن 6 أحرف.',
            'password.max'          => 'كلمة المرور تتجاوز الحد المسموح.',
            'login_device.required' => 'اسم جهاز تسجيل الدخول مطلوب.',
            'login_device.max'      => 'اسم جهاز تسجيل الدخول يتجاوز الحد المسموح 60 حرفًا.',
        ];
    }

    public function attributes(): array
    {
        return [
            'email'        => 'البريد الإلكتروني',
            'password'     => 'كلمة المرور',
            'login_device' => 'اسم جهاز تسجيل الدخول',
        ];
    }
}
