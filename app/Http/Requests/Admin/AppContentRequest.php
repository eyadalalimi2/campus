<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AppContentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('admin')->check();
    }

    public function rules(): array
    {
        return [
            'title'       => ['required','string','max:255'],
            'description' => ['nullable','string'],
            'image'       => ['nullable','image','max:2048'], // 2MB
            'link_url'    => ['nullable','url','max:512'],
            'sort_order'  => ['nullable','integer'],
            'is_active'   => ['required','boolean'],
            'remove_image'=> ['nullable','boolean'],
        ];
    }
}
