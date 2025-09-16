<?php

namespace App\Http\Requests\Api\V1\StudentRequests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'title'      => 'sometimes|nullable|string|max:255',
            'body'       => 'sometimes|nullable|string|max:5000',
            'priority'   => 'sometimes|nullable|in:low,normal,high',
            // لا نسمح بتغيير الفئة/العلاقات عبر واجهة الطالب (ثابتة بعد الإنشاء)
            'attachment' => 'sometimes|nullable|file|max:5120|mimes:jpg,jpeg,png,pdf,webp',
            'close'      => 'sometimes|boolean', // close=true لإغلاق الطلب
        ];
    }
}
