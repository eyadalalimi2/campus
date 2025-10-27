<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ClinicalSubjectPdfRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('clinical_subject_pdf')?->id;
        $fileRule = $this->isMethod('post') ? 'required' : 'nullable';
        return [
            'name' => 'required|string|max:255',
            'content' => 'nullable|string',
            'file' => [$fileRule, 'file', 'mimes:pdf', 'max:20480'], // حتى 20MB
            'order' => 'required|integer|min:0',
            'clinical_subject_id' => 'required|exists:clinical_subjects,id',
        ];
    }
}
