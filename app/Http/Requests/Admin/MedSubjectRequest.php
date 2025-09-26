<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class MedSubjectRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $subject = $this->route('subject');
        $id = $subject?->id;

        return [
            'name' => ['required','string','max:255'],
            'image' => ['nullable','image','mimes:png,jpg,jpeg,webp','max:2048'],
            'scope' => ['required','in:basic,clinical,both'],
            'academic_level' => ['nullable','string','max:255'],
            'order_index' => ['nullable','integer','min:0'],
            'status' => ['required','in:draft,published'],
            'slug' => ['nullable','string','size:8','unique:med_subjects,slug,'.($id??'null')],
            'device_ids' => ['array'],
            'device_ids.*' => ['integer','exists:med_devices,id'],
        ];
    }
}
