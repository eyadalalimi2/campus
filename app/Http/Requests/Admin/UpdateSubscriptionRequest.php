<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSubscriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('admin')->check();
    }

    public function rules(): array
    {
        return [
            // لا نسمح بتعديل plan_id يدوياً لأن الاشتراك مرتبط بكود
            'status'     => ['required', Rule::in(['active','expired','cancelled','pending'])],
            'started_at' => ['nullable','date'],
            'ends_at'    => ['nullable','date','after_or_equal:started_at'],
            'auto_renew' => ['nullable','in:0'], // دائماً 0
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'الرجاء تحديد حالة الاشتراك.',
            'status.in'       => 'الحالة غير صحيحة.',
            'ends_at.after_or_equal' => 'تاريخ الانتهاء يجب أن يكون بعد أو يساوي تاريخ البداية.',
        ];
    }
}
