<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class DeviceRequest extends FormRequest
{
    public function authorize(): bool { return auth('admin')->check(); }

    public function rules(): array
    {
        return [
            'material_id' => 'required|exists:materials,id',
            'name'        => 'required|string|max:200',
            'code'        => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'is_active'   => 'nullable|boolean',
        ];
    }
}
