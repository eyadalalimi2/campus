<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class MedTopicRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $topic = $this->route('topic');
        $id = $topic?->id;

        return [
            'subject_id' => ['required','integer','exists:med_subjects,id'],
            'title' => ['required','string','max:255'],
            'description' => ['nullable','string'],
            'order_index' => ['nullable','integer','min:0'],
            'status' => ['required','in:draft,published'],
            'slug' => ['required','string','max:255','unique:med_topics,slug,'.($id??'null')],
        ];
    }
}
