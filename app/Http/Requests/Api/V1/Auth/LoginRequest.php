<?php

namespace App\Http\Requests\Api\V1\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read string $email
 * @property-read string $password
 * @property-read string $login_device
 */
final class LoginRequest extends FormRequest
{
    /**
     * إيقاف التحقق عند أول خطأ لتقليل زمن الاستجابة.
     */
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email'        => ['required', 'email', 'max:255'],
            'password'     => ['required', 'string', 'min:6', 'max:100'],
            'login_device' => ['required', 'string', 'max:60'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required'        => 'البريد الإلكتروني مطلوب.',
            'email.email'           => 'صيغة البريد الإلكتروني غير صحيحة.',
            'email.max'             => 'البريد الإلكتروني يتجاوز الحد المسموح (255).',
            'password.required'     => 'كلمة المرور مطلوبة.',
            'password.min'          => 'كلمة المرور يجب ألا تقل عن 6 أحرف.',
            'password.max'          => 'كلمة المرور تتجاوز الحد المسموح.',
            'login_device.required' => 'اسم جهاز تسجيل الدخول مطلوب.',
            'login_device.max'      => 'اسم جهاز تسجيل الدخول يتجاوز الحد المسموح (60).',
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

    /**
     * إرجاع القيم المُتحقَّق منها (اختياري لاستخدامه في الكنترولر).
     */
    public function data(): array
    {
        return $this->validated();
    }
}
