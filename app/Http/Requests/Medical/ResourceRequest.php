<?php
namespace App\Http\Requests\Medical;
use Illuminate\Foundation\Http\FormRequest;

class ResourceRequest extends FormRequest {
    public function authorize(): bool { return true; }

    public function rules(): array {
        $isStore = $this->isMethod('post'); // POST = إنشاء، PUT/PATCH = تحديث جزئي
        $req = $isStore ? 'required' : 'sometimes|required';

        return [
            'type'        => "$req|in:YOUTUBE,BOOK,SUMMARY,REFERENCE,QUESTION_BANK",
            'track'       => "$req|in:BASIC,CLINICAL",
            'subject_id'  => "$req|integer|exists:med_subjects,id",

            'system_id'   => 'nullable|integer|exists:med_systems,id',
            'doctor_id'   => 'nullable|integer|exists:med_doctors,id',

            'title'       => "$req|string|max:255",
            'title_en'    => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'language'    => "$req|string|size:2",
            'country'     => 'nullable|string|size:2',
            'year'        => 'nullable|integer|min:1900|max:2100',
            'authors'     => 'nullable|array',

            'level'       => "$req|in:basic,advanced",
            'rating'      => 'nullable|numeric|min:0|max:5',
            'popularity'  => 'nullable|integer|min:0',
            'duration_min'=> 'nullable|integer|min:0',
            'size_mb'     => 'nullable|numeric|min:0',
            'cover_url'   => 'nullable|url|max:255',
            'source_url'  => 'nullable|url|max:255',

            'license'     => "$req|in:OPEN,LINK_ONLY,RESTRICTED",
            'visibility'  => "$req|in:PUBLIC,RESTRICTED",
            'status'      => "$req|in:DRAFT,PUBLISHED,ARCHIVED",
        ];
    }
}
