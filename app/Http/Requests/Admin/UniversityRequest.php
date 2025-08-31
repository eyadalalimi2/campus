<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UniversityRequest extends FormRequest
{
    public function authorize(): bool { return auth('admin')->check(); }

    public function rules(): array {
        $id = $this->route('university')?->id;
        return [
            'name'           => 'required|string|max:200',
            'slug'           => 'required|string|max:100|unique:universities,slug,'.($id ?? 'NULL').',id',
            'code'           => 'nullable|string|max:20|unique:universities,code,'.($id ?? 'NULL').',id',
            'logo'           => 'nullable|image|max:1024',
            'favicon'        => 'nullable|image|max:512',
            'primary_color'  => 'required|string|max:20',
            'secondary_color'=> 'required|string|max:20',
            'is_active'      => 'nullable|boolean',
        ];
    }
}
