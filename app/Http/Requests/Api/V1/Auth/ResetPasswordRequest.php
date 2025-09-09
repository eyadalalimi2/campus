<?php

namespace App\Http\Requests\Api\V1\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read string $email
 * @property-read string $token
 * @property-read string $password
 * @property-read string $password_confirmation
 */
final class ResetPasswordRequest extends FormRequest
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
            'email'                 => ['required', 'email', 'max:255'],
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
            'email.max'         => 'البريد الإلكتروني يتجاوز الحد المسموح (255).',

            'token.required'    => 'رمز استعادة كلمة المرور مطلوب.',
            'token.min'         => 'رمز الاستعادة قصير جدًا.',
            'token.max'         => 'رمز الاستعادة يتجاوز الحد المسموح.',

            'password.required' => 'كلمة المرور الجديدة مطلوبة.',
            'password.min'      => 'كلمة المرور الجديدة يجب ألا تقل عن 6 أحرف.',
            'password.confirmed'=> 'تأكيد كلمة المرور لا يطابق كلمة المرور الجديدة.',

            'password_confirmation.required' => 'تأكيد كلمة المرور مطلوب.',
            'password_confirmation.min'      => 'تأكيد كلمة المرور يجب ألا يقل عن 6 أحرف.',
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

    /**
     * القيم المُتحقَّق منها لاستخدامها في الكنترولر.
     */
    public function data(): array
    {
        return $this->validated();
    }
}
