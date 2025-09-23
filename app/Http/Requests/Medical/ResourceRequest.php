<?php
namespace App\Http\Requests\Medical;
use Illuminate\Foundation\Http\FormRequest;

class ResourceRequest extends FormRequest {
    public function authorize(): bool { return true; }
    public function rules(): array {
        return [
            'type' => 'required|in:YOUTUBE,BOOK,SUMMARY,REFERENCE,QUESTION_BANK',
            'track' => 'required|in:BASIC,CLINICAL',
            'subject_id' => 'required|integer|exists:med_subjects,id',
            'system_id' => 'nullable|integer|exists:med_systems,id',
            'doctor_id' => 'nullable|integer|exists:med_doctors,id',
            'title' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'language' => 'required|string|size:2',
            'country' => 'nullable|string|size:2',
            'year' => 'nullable|integer|min:1900|max:2100',
            'authors' => 'nullable|array',
            'level' => 'required|in:basic,advanced',
            'rating' => 'nullable|numeric|min:0|max:5',
            'popularity' => 'nullable|integer|min:0',
            'duration_min' => 'nullable|integer|min:0',
            'size_mb' => 'nullable|numeric|min:0',
            'cover_url' => 'nullable|url|max:255',
            'source_url' => 'nullable|url|max:255',
            'license' => 'required|in:OPEN,LINK_ONLY,RESTRICTED',
            'visibility' => 'required|in:PUBLIC,RESTRICTED',
            'status' => 'required|in:DRAFT,PUBLISHED,ARCHIVED'
        ];
    }
}
