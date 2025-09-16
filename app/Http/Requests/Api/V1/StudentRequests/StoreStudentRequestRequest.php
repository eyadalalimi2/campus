<?php

namespace App\Http\Requests\Api\V1\StudentRequests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check(); // طالب مسجل دخول
    }

    public function rules(): array
    {
        return [
            'category'    => 'required|in:general,material,account,technical,other',
            'title'       => 'required|string|max:255',
            'body'        => 'nullable|string|max:5000',
            'priority'    => 'nullable|in:low,normal,high',
            'material_id' => 'nullable|exists:materials,id',
            'content_id'  => 'nullable|exists:contents,id',
            'attachment'  => 'nullable|file|max:5120|mimes:jpg,jpeg,png,pdf,webp',
        ];
    }
}
