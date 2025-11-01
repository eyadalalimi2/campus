<?php

namespace App\Http\Requests\Api\V1\Reviews;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'comment' => ['nullable','string','max:2000'],
            'rating'  => ['nullable','integer','min:1','max:5'],
        ];
    }
}
