<?php

namespace App\Http\Requests\Api\V1\Complaint;

use Illuminate\Foundation\Http\FormRequest;

class StoreComplaintRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'type'        => 'required|in:content,asset,user,bug,abuse,other',
            'subject'     => 'required|string|max:255',
            'body'        => 'nullable|string|max:5000',
            'severity'    => 'nullable|in:low,medium,high,critical',
            'target_type' => 'nullable|string|max:50',
            'target_id'   => 'nullable|integer|min:1',
            'attachment'  => 'nullable|file|max:5120|mimes:jpg,jpeg,png,pdf,webp',
        ];
    }
}
