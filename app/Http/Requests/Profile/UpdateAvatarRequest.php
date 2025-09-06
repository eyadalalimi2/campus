<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAvatarRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'avatar' => ['required','image','mimes:jpg,jpeg,png,webp','max:2048'], // 2MB
        ];
    }
}
