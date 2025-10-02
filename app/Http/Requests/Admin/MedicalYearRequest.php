<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class MedicalYearRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // أضف صلاحيات إن وُجدت
    }

    public function rules(): array
    {
        return [
            'major_id'    => ['required','exists:majors,id'],
            'year_number' => ['required','integer','min:1','max:6'],
            'is_active'   => ['nullable','boolean'],
            'sort_order'  => ['nullable','integer','min:0'],
        ];
    }
}