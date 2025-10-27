<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class MedicalSystemSubjectRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'system_id'   => ['required','exists:MedicalSystems,id'],
            // allow either a single subject_id or an array of subject_ids
            'subject_id'   => ['nullable','required_without:subject_ids','exists:MedicalSubjects,id'],
            'subject_ids'  => ['nullable','array','required_without:subject_id'],
            'subject_ids.*'=> ['exists:MedicalSubjects,id'],
        ];
    }
}