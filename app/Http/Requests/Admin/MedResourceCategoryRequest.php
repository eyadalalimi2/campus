<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class MedResourceCategoryRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $cat = $this->route('resource_category');
        $id = $cat?->id;

        return [
            'name' => ['required','string','max:255'],
            'code' => ['required','alpha_dash','max:50','unique:med_resource_categories,code,'.($id??'null')],
            'order_index' => ['nullable','integer','min:0'],
            'active' => ['required','boolean'],
        ];
    }
}
