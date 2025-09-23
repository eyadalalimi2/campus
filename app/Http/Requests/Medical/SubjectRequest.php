<?php
namespace App\Http\Requests\Medical;
use Illuminate\Foundation\Http\FormRequest;

class SubjectRequest extends FormRequest {
    public function authorize(): bool { return true; }
    public function rules(): array {
        $id = $this->route('subject');
        return [
            'code' => 'required|string|max:50|unique:med_subjects,code,'.($id?->id ?? 'null'),
            'name_ar' => 'required|string|max:191',
            'name_en' => 'nullable|string|max:191',
            'track_scope' => 'required|in:BASIC,CLINICAL,BOTH',
            'is_active' => 'boolean'
        ];
    }
}
