<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AssetUpdateRequest extends FormRequest
{
    public function authorize(): bool { return auth()->user()?->can('manage-assets') ?? false; }

    public function rules(): array
    {
        return [
            'title' => ['sometimes','string','max:190'],
            'public_major_ids' => ['nullable','array','min:1'],
            'public_major_ids.*' => ['integer','exists:public_majors,id'],
            'primary_public_major_id' => ['nullable','integer','in_array:public_major_ids.*'],
            'public_major_priorities' => ['nullable','array'],
        ];
    }
}
