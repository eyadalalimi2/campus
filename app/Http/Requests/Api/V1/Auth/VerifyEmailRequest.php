<?php

namespace App\Http\Requests\Api\V1\Auth;

use Illuminate\Foundation\Http\FormRequest;

class VerifyEmailRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

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
            'email' => ['required', 'string', 'email', 'max:255'],
            // كود تحقق يصل للمستخدم عبر البريد/القناة المناسبة (عدد أرقام مرن 4–8)
            'code'  => ['required', 'string', 'regex:/^[0-9]{4,8}$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'البريد الإلكتروني مطلوب.',
            'email.email'    => 'صيغة البريد الإلكتروني غير صحيحة.',
            'email.max'      => 'البريد الإلكتروني يتجاوز الحد المسموح 255 حرفًا.',
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
}
