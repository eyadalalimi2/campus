<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AssetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('admin')->check();
    }

    public function rules(): array
    {
        return [
            'material_id'   => ['nullable','exists:materials,id'],
            'device_id'     => ['nullable','exists:devices,id'],
            'doctor_id'     => ['nullable','exists:doctors,id'],
            'discipline_id' => ['nullable','exists:disciplines,id'],
            'program_id'    => ['nullable','exists:programs,id'],
            'category'      => ['required', Rule::in(['youtube','file','reference','question_bank','curriculum','book'])],
            'title'         => ['required','string','max:255'],
            'description'   => ['nullable','string'],
            'status'        => ['required', Rule::in(['draft','in_review','published','archived'])],
            'video_url'     => ['nullable','url','required_if:category,youtube'],
            'file'          => ['nullable','file','max:20480','required_if:category,file'],
            'external_url'  => ['nullable','url','required_if:category,reference,question_bank,curriculum,book'],
            'is_active'     => ['boolean'],
        ];
    }
}
