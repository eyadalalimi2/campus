<?php

namespace App\Http\Requests\Api\V1\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
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
     * تطبيع البريد الإلكتروني قبل التحقق
     */
    protected function prepareForValidation(): void
    {
        $email = $this->input('email');
        $this->merge([
            'email' => $email !== null ? mb_strtolower(trim($email)) : $email,
        ]);
    }

    public function rules(): array
    {
        return [
            'email'                 => ['required', 'string', 'email', 'max:255'],
            'token'                 => ['required', 'string', 'min:10', 'max:255'],
            'password'              => ['required', 'string', 'min:6', 'confirmed'],
            'password_confirmation' => ['required', 'string', 'min:6'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required'    => 'البريد الإلكتروني مطلوب.',
            'email.email'       => 'صيغة البريد الإلكتروني غير صحيحة.',
            'email.max'         => 'البريد الإلكتروني يتجاوز الحد المسموح 255 حرفًا.',
            'token.required'    => 'رمز استعادة كلمة المرور مطلوب.',
            'token.min'         => 'رمز الاستعادة قصير جدًا.',
            'password.required' => 'كلمة المرور الجديدة مطلوبة.',
            'password.min'      => 'كلمة المرور الجديدة يجب ألا تقل عن 6 أحرف.',
            'password.confirmed'=> 'تأكيد كلمة المرور لا يطابق كلمة المرور الجديدة.',
            'password_confirmation.required' => 'تأكيد كلمة المرور مطلوب.',
        ];
    }

    public function attributes(): array
    {
        return [
            'email'                 => 'البريد الإلكتروني',
            'token'                 => 'رمز استعادة كلمة المرور',
            'password'              => 'كلمة المرور الجديدة',
            'password_confirmation' => 'تأكيد كلمة المرور الجديدة',
        ];
    }
}
