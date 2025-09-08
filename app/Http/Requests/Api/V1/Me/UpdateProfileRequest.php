<?php

namespace App\Http\Requests\Api\V1\Me;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    /**
     * إيقاف التحقق عند أول خطأ
     */
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    /**
     * تجهيز المدخلات قبل التحقق
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'student_number'     => $this->filled('student_number') ? trim($this->input('student_number')) : null,
            'name'               => $this->filled('name') ? trim($this->input('name')) : null,
            'phone'              => $this->filled('phone') ? trim($this->input('phone')) : null,
            'profile_photo_path' => $this->filled('profile_photo_path') ? trim($this->input('profile_photo_path')) : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'student_number'     => ['nullable', 'string', 'max:255'],
            'name'               => ['nullable', 'string', 'max:255'],
            'phone'              => ['nullable', 'string', 'max:20'],
            'country_id'         => ['nullable', 'integer', 'exists:countries,id'],
            'university_id'      => ['nullable', 'integer', 'exists:universities,id'],
            'college_id'         => ['nullable', 'integer', 'exists:colleges,id'],
            'major_id'           => ['nullable', 'integer', 'exists:majors,id'],
            'level'              => ['nullable', 'integer', 'min:1', 'max:20'],
            'gender'             => ['nullable', 'in:male,female'],
            'profile_photo_path' => ['nullable', 'string', 'max:255', 'regex:/\.(jpg|jpeg|png|webp)$/i'],
        ];
    }

    public function messages(): array
    {
        return [
            'student_number.max'      => 'الرقم الجامعي يجب ألا يتجاوز 255 حرفًا.',
            'name.max'               => 'الاسم يجب ألا يتجاوز 255 حرفًا.',
            'phone.max'              => 'رقم الهاتف يجب ألا يتجاوز 20 رقمًا.',
            'country_id.exists'      => 'الدولة المحددة غير موجودة.',
            'university_id.exists'   => 'الجامعة المحددة غير موجودة.',
            'college_id.exists'      => 'الكلية المحددة غير موجودة.',
            'major_id.exists'        => 'التخصص المحدد غير موجود.',
            'level.min'              => 'الحد الأدنى للمستوى هو 1.',
            'level.max'              => 'الحد الأقصى للمستوى هو 20.',
            'gender.in'              => 'النوع يجب أن يكون ذكر أو أنثى.',
            'profile_photo_path.regex' => 'صورة الملف الشخصي يجب أن تكون بصيغة jpg أو jpeg أو png أو webp.',
            'profile_photo_path.max'   => 'مسار صورة الملف الشخصي طويل جدًا.',
        ];
    }

    public function attributes(): array
    {
        return [
            'student_number'     => 'الرقم الجامعي',
            'name'               => 'الاسم',
            'phone'              => 'رقم الهاتف',
            'country_id'         => 'الدولة',
            'university_id'      => 'الجامعة',
            'college_id'         => 'الكلية',
            'major_id'           => 'التخصص',
            'level'              => 'المستوى الدراسي',
            'gender'             => 'النوع',
            'profile_photo_path' => 'صورة الملف الشخصي',
        ];
    }
}
