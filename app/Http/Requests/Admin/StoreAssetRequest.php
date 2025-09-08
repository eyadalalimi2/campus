<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreAssetRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'material_id'   => 'nullable|exists:materials,id',
            'device_id'     => 'nullable|exists:devices,id',
            'doctor_id'     => 'nullable|exists:doctors,id',
            'discipline_id' => 'nullable|exists:disciplines,id',
            'program_id'    => 'nullable|exists:programs,id',
            'category'      => 'required|in:youtube,file,reference,question_bank,curriculum,book',
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string',
            'status'        => 'required|in:draft,in_review,published,archived',
            'published_at'  => 'nullable|date',
            'published_by_admin_id' => 'nullable|exists:admins,id',
            'video_url'     => 'nullable|url|max:255',
            'file_path'     => 'nullable|string|max:255',
            'external_url'  => 'nullable|url|max:255',
            'is_active'     => 'boolean',
        ];
    }
}
