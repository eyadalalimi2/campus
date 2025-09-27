<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BlogRequest extends FormRequest
{
    public function authorize(): bool { return auth('admin')->check(); }

    public function rules(): array
    {
        $id = $this->route('blog')?->id;

        return [
            'title'   => ['required','string','max:255'],
            'slug'    => ['required','string','max:255', Rule::unique('blogs','slug')->ignore($id)],
            'excerpt' => ['nullable','string','max:500'],
            'body'    => ['nullable','string'],

            'status'  => ['required', Rule::in(['draft','published','archived'])],
            'published_at' => ['nullable','date'],

            'university_id' => ['nullable','exists:universities,id'],
            'doctor_id'     => ['nullable','exists:doctors,id'],

            'cover_image'   => ['nullable','image','max:2048'], // 2MB
            'is_active'     => ['nullable','boolean'],
        ];
    }
}
