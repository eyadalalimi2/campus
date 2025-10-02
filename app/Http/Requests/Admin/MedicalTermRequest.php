<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class MedicalTermRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'year_id'     => ['required','exists:MedicalYears,id'],
            'term_number' => ['required','integer','min:1','max:3'],
            'is_active'   => ['nullable','boolean'],
            'sort_order'  => ['nullable','integer','min:0'],
        ];
    }
}