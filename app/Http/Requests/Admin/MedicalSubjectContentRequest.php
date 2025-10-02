<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class MedicalSubjectContentRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'subject_id' => ['required','exists:MedicalSubjects,id'],
            'content_id' => ['required','exists:contents,id'],
            'sort_order' => ['nullable','integer','min:0'],
            'is_primary' => ['nullable','boolean'],
            'notes'      => ['nullable','string'],
        ];
    }
}