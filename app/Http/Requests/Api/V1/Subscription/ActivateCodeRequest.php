<?php

namespace App\Http\Requests\Api\V1\Subscription;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read string $code
 */
final class ActivateCodeRequest extends FormRequest
{
    /**
     * تسريع الاستجابة بإيقاف التحقق عند أول خطأ.
     */
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // الطول الأقصى يتوافق مع العمود (varchar(64))
            // السماح بحروف/أرقام و ( - _ . ) ومسافة واحدة داخلية إن كانت الأكواد تحتويها
            'code' => [
                'required',
                'string',
                'max:64',
                'regex:/^[A-Za-z0-9\-\._ ]+$/',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'رمز التفعيل مطلوب.',
            'code.string'   => 'رمز التفعيل يجب أن يكون نصًا.',
            'code.max'      => 'رمز التفعيل يتجاوز الحد الأقصى المسموح (64 حرفًا).',
            'code.regex'    => 'رمز التفعيل يجب أن يتكوّن من أحرف أو أرقام ويمكن أن يحتوي على (- _ .) أو مسافات.',
        ];
    }

    public function attributes(): array
    {
        return [
            'code' => 'رمز التفعيل',
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
