<?php

namespace App\Http\Requests\Api\V1\Me;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
    /**
     * تسريع الاستجابة بإيقاف التحقق عند أول خطأ
     */
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    /**
     * تطبيع المدخلات قبل التحقق
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'current_password'         => $this->input('current_password') !== null ? trim($this->input('current_password')) : null,
            'new_password'             => $this->input('new_password') !== null ? trim($this->input('new_password')) : null,
            'new_password_confirmation'=> $this->input('new_password_confirmation') !== null ? trim($this->input('new_password_confirmation')) : null,
        ]);
    }

    public function rules(): array
    {
        return [
            // التحقق من كلمة المرور الحالية (نستخدم حارس sanctum لأن واجهتنا API)
            'current_password'          => ['required', 'string', 'min:6', 'max:100', 'current_password:sanctum'],

            // كلمة المرور الجديدة + تأكيدها + عدم مطابقتها للحالية
            'new_password'              => ['required', 'string', 'min:6', 'max:100', 'confirmed', 'different:current_password'],
            'new_password_confirmation' => ['required', 'string', 'min:6', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'current_password.required' => 'كلمة المرور الحالية مطلوبة.',
            'current_password.min'      => 'كلمة المرور الحالية يجب ألا تقل عن 6 أحرف.',
            'current_password.max'      => 'كلمة المرور الحالية تتجاوز الحد المسموح.',
            'current_password.current_password' => 'كلمة المرور الحالية غير صحيحة.',

            'new_password.required'     => 'كلمة المرور الجديدة مطلوبة.',
            'new_password.min'          => 'كلمة المرور الجديدة يجب ألا تقل عن 6 أحرف.',
            'new_password.max'          => 'كلمة المرور الجديدة تتجاوز الحد المسموح.',
            'new_password.confirmed'    => 'تأكيد كلمة المرور الجديدة لا يطابق.',
            'new_password.different'    => 'كلمة المرور الجديدة يجب أن تكون مختلفة عن الحالية.',

            'new_password_confirmation.required' => 'تأكيد كلمة المرور الجديدة مطلوب.',
            'new_password_confirmation.min'      => 'تأكيد كلمة المرور يجب ألا يقل عن 6 أحرف.',
            'new_password_confirmation.max'      => 'تأكيد كلمة المرور يتجاوز الحد المسموح.',
        ];
    }

    public function attributes(): array
    {
        return [
            'current_password'           => 'كلمة المرور الحالية',
            'new_password'               => 'كلمة المرور الجديدة',
            'new_password_confirmation'  => 'تأكيد كلمة المرور الجديدة',
        ];
    }
}
