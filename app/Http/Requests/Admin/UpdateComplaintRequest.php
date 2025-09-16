<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateComplaintRequest extends FormRequest
{
    public function authorize(): bool
    {
        // فعّل صلاحياتك/Policy إن وجدت
        return auth()->guard('admin')->check();
    }

    public function rules(): array
    {
        return [
            'status' => 'nullable|in:open,triaged,in_progress,resolved,rejected,closed',
            'severity' => 'nullable|in:low,medium,high,critical',
            'assigned_admin_id' => 'nullable|exists:admins,id',
            'note' => 'nullable|string|max:2000',
            'close_now' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'status.in' => 'قيمة الحالة غير صالحة.',
            'severity.in' => 'قيمة الخطورة غير صالحة.',
            'assigned_admin_id.exists' => 'المستخدم المعيّن غير موجود.',
        ];
    }
}
