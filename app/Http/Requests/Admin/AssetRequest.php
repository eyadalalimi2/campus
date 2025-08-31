<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AssetRequest extends FormRequest
{
    public function authorize(): bool { return auth('admin')->check(); }

    public function rules(): array
    {
        $rules = [
            'category'   => ['required', Rule::in(['youtube','file','reference','question_bank','curriculum','book'])],
            'title'      => 'required|string|max:255',
            'description'=> 'nullable|string',

            'material_id'=> 'required|exists:materials,id',
            'device_id'  => 'nullable|exists:devices,id',
            'doctor_id'  => 'nullable|exists:doctors,id',

            'is_active'  => 'nullable|boolean',
        ];

        $cat = $this->input('category');

        if ($cat === 'youtube') {
            $rules['video_url'] = 'required|url';
        } elseif ($cat === 'file') {
            $rules['file'] = 'required|file|max:20480';
        } else {
            // reference/question_bank/curriculum/book
            $rules['file']        = 'nullable|file|max:20480';
            $rules['external_url']= 'nullable|url';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'file.required' => 'الملف مطلوب عندما يكون النوع ملفًا.',
        ];
    }
}
