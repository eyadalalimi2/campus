<?php

namespace App\Http\Requests\Api\V1\Me;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read string|null $student_number
 * @property-read string|null $name
 * @property-read string|null $phone
 * @property-read int|null    $country_id
 * @property-read int|null    $university_id
 * @property-read int|null    $college_id
 * @property-read int|null    $major_id
 * @property-read int|null    $level
 * @property-read string|null $gender
 * @property-read string|null $profile_photo_path
 */
final class UpdateProfileRequest extends FormRequest
{
    /**
     * إيقاف التحقق عند أول خطأ لتقليل زمن الاستجابة.
     */
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_number'     => ['nullable', 'string', 'max:255'],
            'name'               => ['nullable', 'string', 'max:255'],
            'phone'              => ['nullable', 'string', 'max:20'],

            'country_id'         => ['nullable', 'integer', 'exists:countries,id'],
            'university_id'      => ['nullable', 'integer', 'exists:universities,id'],
            'branch_id'          => ['nullable', 'integer', 'exists:university_branches,id'],
            'college_id'         => ['nullable', 'integer', 'exists:colleges,id'],
            'major_id'           => ['nullable', 'integer', 'exists:majors,id'],
            'level'              => ['nullable', 'integer', 'min:1', 'max:20'],
            'current_term'       => ['nullable', 'integer', 'min:0'],
            'public_college_id'  => ['nullable', 'integer', 'exists:public_colleges,id'],
            'public_major_id'    => ['nullable', 'integer', 'exists:public_majors,id'],

            'gender'             => ['nullable', 'in:male,female'],
            'profile_photo_path' => ['nullable', 'string', 'max:255', 'regex:/\.(jpg|jpeg|png|webp)$/i'],
        ];
    }

    public function messages(): array
    {
        return [
            'student_number.max'        => 'الرقم الجامعي يجب ألا يتجاوز 255 حرفًا.',
            'name.max'                  => 'الاسم يجب ألا يتجاوز 255 حرفًا.',
            'phone.max'                 => 'رقم الهاتف يجب ألا يتجاوز 20 خانة.',
            'country_id.exists'         => 'الدولة المحددة غير موجودة.',
            'university_id.exists'      => 'الجامعة المحددة غير موجودة.',
            'branch_id.exists'          => 'الفرع المحدد غير موجود.',
            'college_id.exists'         => 'الكلية المحددة غير موجودة.',
            'major_id.exists'           => 'التخصص المحدد غير موجود.',
            'level.min'                 => 'الحد الأدنى للمستوى هو 1.',
            'level.max'                 => 'الحد الأقصى للمستوى هو 20.',
            'current_term.min'          => 'الفصل الحالي أقل من الصفر.',
            'public_college_id.exists'  => 'الكلية العامة المحددة غير موجودة.',
            'public_major_id.exists'    => 'التخصص العام المحدد غير موجود.',
            'gender.in'                 => 'قيمة النوع يجب أن تكون male أو female.',
            'profile_photo_path.regex'  => 'صورة الملف الشخصي يجب أن تكون بصيغة jpg أو jpeg أو png أو webp.',
            'profile_photo_path.max'    => 'مسار صورة الملف الشخصي طويل جدًا.',
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
            'branch_id'          => 'الفرع',
            'college_id'         => 'الكلية',
            'major_id'           => 'التخصص',
            'level'              => 'المستوى الدراسي',
            'current_term'       => 'الفصل الحالي',
            'public_college_id'  => 'الكلية العامة',
            'public_major_id'    => 'التخصص العام',
            'gender'             => 'النوع',
            'profile_photo_path' => 'صورة الملف الشخصي',
        ];
    }

    /**
     * القيم المُتحقَّق منها لاستخدامها في الكنترولر.
     */
    public function data(): array
    {
        return $this->validated();
    }
}
