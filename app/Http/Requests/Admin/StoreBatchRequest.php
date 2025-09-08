<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('admin')->check();
    }

    public function rules(): array
    {
        return [
            'name'           => ['required','string','max:150'],
            'notes'          => ['nullable','string'],
            'plan_id'        => ['required','integer','exists:plans,id'],
            'university_id'  => ['nullable','integer','exists:universities,id'],
            'college_id'     => ['nullable','integer','exists:colleges,id'],
            'major_id'       => ['nullable','integer','exists:majors,id'],
            'quantity'       => ['required','integer','min:1','max:100000'],
            'status'         => ['required', Rule::in(['draft','active','disabled','archived'])],
            'duration_days'  => ['required','integer','min:1','max:2000'],
            'start_policy'   => ['required', Rule::in(['on_redeem','fixed_start'])],
            'starts_on'      => ['nullable','date'],
            'valid_from'     => ['nullable','date'],
            'valid_until'    => ['nullable','date','after_or_equal:valid_from'],
            'code_prefix'    => ['nullable','string','max:24'],
            'code_length'    => ['required','integer','min:8','max:32'],
        ];
    }
}
