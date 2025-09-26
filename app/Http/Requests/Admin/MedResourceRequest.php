<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class MedResourceRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'subject_id' => ['required','integer','exists:med_subjects,id'],
            'topic_id' => ['nullable','integer','exists:med_topics,id'],
            'category_id' => ['required','integer','exists:med_resource_categories,id'],
            'title' => ['required','string','max:255'],
            'description' => ['nullable','string'],
            'file' => ['nullable','file','mimes:pdf','max:30000'], // 30MB
            'file_url' => ['nullable','url','max:1000'],
            'file_size_bytes' => ['nullable','integer','min:0'],
            'pages_count' => ['nullable','integer','min:1'],
            'order_index' => ['nullable','integer','min:0'],
            'status' => ['required','in:draft,published'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function($v){
            $hasUpload = $this->hasFile('file');
            $hasUrl = filled($this->input('file_url'));
            if (!$hasUpload && !$hasUrl) {
                $v->errors()->add('file', 'يجب تحديد ملف PDF أو رابط ملف.');
            }
        });
    }
}
