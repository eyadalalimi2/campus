<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class MedicalSystemRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'year_id'       => ['required','exists:MedicalYears,id'],
            'med_device_id' => ['required','exists:med_devices,id'],
            'display_name'  => ['nullable','string','max:255'],
            'notes'         => ['nullable','string'],
            'is_active'     => ['nullable','boolean'],
            'sort_order'    => ['nullable','integer','min:0'],
        ];
    }
}