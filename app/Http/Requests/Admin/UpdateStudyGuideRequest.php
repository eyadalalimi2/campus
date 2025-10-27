<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudyGuideRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('admin')->check();
    }

    public function rules(): array
    {
        return [
            'title' => ['required','string','max:255'],
            'description' => ['nullable','string'],
            'youtube_url' => ['required','url','max:500', function($attr,$value,$fail){
                if (!preg_match('~(youtube\.com|youtu\.be)~i', $value)) {
                    $fail('يجب إدخال رابط يوتيوب صالح.');
                }
            }],
        ];
    }
}
