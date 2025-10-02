<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class MedicalSystemSubjectRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'system_id'  => ['required','exists:MedicalSystems,id'],
            'subject_id' => ['required','exists:MedicalSubjects,id'],
        ];
    }
}