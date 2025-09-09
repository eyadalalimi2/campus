<?php

namespace App\Http\Requests\Api\V1\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read string $email
 * @property-read string $code
 */
final class VerifyEmailRequest extends FormRequest
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
            'email' => ['required', 'email', 'max:255'],
            // كود تحقق يصل للمستخدم عبر البريد/القناة المناسبة (عدد أرقام مرن 4–8)
            'code'  => ['required', 'regex:/^[0-9]{4,8}$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'البريد الإلكتروني مطلوب.',
            'email.email'    => 'صيغة البريد الإلكتروني غير صحيحة.',
            'email.max'      => 'البريد الإلكتروني يتجاوز الحد المسموح (255).',
            'code.required'  => 'رمز التحقق مطلوب.',
            'code.regex'     => 'رمز التحقق يجب أن يتكون من 4 إلى 8 أرقام.',
        ];
    }

    public function attributes(): array
    {
        return [
            'email' => 'البريد الإلكتروني',
            'code'  => 'رمز التحقق',
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
