<?php

namespace App\Http\Requests\Api\V1\Me;

use Illuminate\Foundation\Http\FormRequest;

class UploadPhotoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // المستخدم موثّق بالميدلوير
    }

    public function rules(): array
    {
        return [
            'photo' => ['required', 'file', 'mimetypes:image/*', 'max:12288'], // 12MB
        ];
    }

    public function messages(): array
    {
        return [
            'photo.required'  => 'الرجاء اختيار صورة.',
            'photo.file'      => 'الملف المرفوع غير صالح.',
            'photo.mimetypes' => 'يجب أن يكون الملف صورة (جميع صيغ الصور مقبولة).',
            'photo.max'       => 'حجم الصورة كبير جدًا. الحد الأقصى 12MB.',
        ];
    }
}
