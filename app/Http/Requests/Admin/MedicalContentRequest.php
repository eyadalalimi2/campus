<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Content;

class MedicalContentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('admin')->check();
    }

    public function rules(): array
    {
        $id   = $this->route('medical_content')?->id; // عند التعديل
        $type = $this->input('type');

        $rules = [
            'title'         => ['required','string','max:255'],
            'description'   => ['nullable','string'],
            'type'          => ['required','in:'.Content::TYPE_FILE.','.Content::TYPE_LINK],
            'university_id' => ['required','integer','exists:universities,id'],
            'branch_id'     => ['nullable','integer','exists:university_branches,id'],
            'college_id'    => ['nullable','integer','exists:colleges,id'],
            'major_id'      => ['nullable','integer','exists:majors,id'],
            'status'        => ['required','in:'.Content::STATUS_DRAFT.','.Content::STATUS_IN_REVIEW.','.Content::STATUS_PUBLISHED.','.Content::STATUS_ARCHIVED],
            'is_active'     => ['nullable','boolean'],
            'material_id'   => ['nullable','integer','exists:materials,id'],
            'doctor_id'     => ['nullable','integer','exists:doctors,id'],
        ];

        // شرطية الحقول حسب النوع
        if ($type === Content::TYPE_FILE) {
            $rules['file'] = [$id ? 'nullable' : 'required','file','mimes:pdf','max:51200']; // 50MB
            $rules['source_url'] = ['nullable','url'];
        } elseif ($type === Content::TYPE_LINK) {
            $rules['source_url'] = ['required','url','max:255'];
            $rules['file']       = ['nullable']; // يتم تجاهل أي ملف
        } else {
            // في حال لم يصل type بعد، اسمح بشرط عام (لن يمرّ بسبب in أعلاه)
            $rules['file']       = ['nullable'];
            $rules['source_url'] = ['nullable','url'];
        }

        return $rules;
    }

    public function attributes(): array
    {
        return [
            'title'         => 'العنوان',
            'description'   => 'الوصف',
            'type'          => 'نوع المحتوى',
            'file'          => 'الملف',
            'source_url'    => 'الرابط',
            'university_id' => 'الجامعة',
            'branch_id'     => 'الفرع',
            'college_id'    => 'الكلية',
            'major_id'      => 'التخصص',
            'status'        => 'حالة النشر',
            'is_active'     => 'مفعل',
        ];
    }
}