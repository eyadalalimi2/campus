<?php

namespace App\Http\Requests\Api\V1\Complaint;

use Illuminate\Foundation\Http\FormRequest;

class UpdateComplaintRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'body'       => 'sometimes|nullable|string|max:5000',
            'close'      => 'sometimes|boolean', // close=true لإغلاق الشكوى
            'attachment' => 'sometimes|nullable|file|max:5120|mimes:jpg,jpeg,png,pdf,webp',
        ];
    }
}
