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
            // allow either a single content_id or an array of content_ids
            'content_id'   => ['nullable','required_without:content_ids','exists:contents,id'],
            'content_ids'  => ['nullable','array','required_without:content_id'],
            'content_ids.*'=> ['exists:contents,id'],
            'sort_order' => ['nullable','integer','min:0'],
            'is_primary' => ['nullable','boolean'],
            'notes'      => ['nullable','string'],
        ];
    }
}