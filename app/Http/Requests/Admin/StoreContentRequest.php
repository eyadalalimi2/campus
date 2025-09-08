<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreContentRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'title'                => 'required|string|max:255',
            'description'          => 'nullable|string',
            'type'                 => 'required|in:file,video,link',
            'source_url'           => 'nullable|url|max:255',
            'file_path'            => 'nullable|string|max:255',
            'university_id'        => 'required|exists:universities,id',
            'college_id'           => 'nullable|exists:colleges,id',
            'major_id'             => 'nullable|exists:majors,id',
            'material_id'          => 'nullable|exists:materials,id',
            'doctor_id'            => 'nullable|exists:doctors,id',
            'status'               => 'required|in:draft,in_review,published,archived',
            'published_at'         => 'nullable|date',
            'published_by_admin_id'=> 'nullable|exists:admins,id',
            'is_active'            => 'boolean',
            'version'              => 'nullable|integer|min:1',
            'changelog'            => 'nullable|string',
        ];
    }
}
