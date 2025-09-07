<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('admin')->check();
    }

    public function rules(): array
    {
        return [
            'title'          => ['required','string','max:255'],
            'description'    => ['nullable','string'],
            'type'           => ['required', Rule::in(['file','video','link'])],
            'source_url'     => ['nullable','url','required_if:type,video,link'],
            'file'           => ['nullable','file','max:20480','required_if:type,file'],
            'university_id'  => ['required','exists:universities,id'],
            'college_id'     => ['nullable','exists:colleges,id'],
            'major_id'       => ['nullable','exists:majors,id'],
            'material_id'    => ['nullable','exists:materials,id'],
            'doctor_id'      => ['nullable','exists:doctors,id'],
            'status'         => ['required', Rule::in(['draft','in_review','published','archived'])],
            'device_ids'     => ['array'],
            'device_ids.*'   => ['exists:devices,id'],
            'is_active'      => ['boolean'],
            'version'        => ['nullable','integer'],
            'changelog'      => ['nullable','string'],
        ];
    }
}
