<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\UniversityBranch;
use App\Models\College;
use App\Models\Major;
use App\Models\Material;
use App\Models\Doctor;

class StoreContentRequest extends FormRequest
{
    public function authorize(): bool
    {
        // لو عندك حارس admins مفعّل:
        return auth('admin')->check();
        // أو ارجع true لو ما عندك تفويض متقدم.
        // return true;
    }

    public function rules(): array
    {
        return [
            'title'       => ['required','string','max:255'],
            'description' => ['nullable','string'],

            'type'       => ['required', Rule::in(['file','video','link'])],
            'source_url' => ['nullable','url','required_if:type,video,link','max:255'],
            'file'       => ['nullable','file','max:20480','required_if:type,file'], // يرفعه الكونترولر

            // التسلسل الهرمي:
            'university_id' => ['required','exists:universities,id'],
            'branch_id'     => ['nullable','exists:university_branches,id'],
            'college_id'    => ['nullable','exists:colleges,id'],
            'major_id'      => ['nullable','exists:majors,id'],

            // روابط أخرى اختيارية:
            'material_id' => ['nullable','exists:materials,id'],
            'doctor_id'   => ['nullable','exists:doctors,id'],

            // الحالة والنشاط
            'status'    => ['required', Rule::in(['draft','in_review','published','archived'])],
            'is_active' => ['nullable','boolean'],

            // أجهزة الربط
            'device_ids'   => ['nullable','array'],
            'device_ids.*' => ['integer','exists:devices,id'],

            // إصدار/سجل تغييرات (اختياري)
            'version'   => ['nullable','integer','min:1'],
            'changelog' => ['nullable','string'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($v) {
            $universityId = (int) $this->input('university_id');
            $branchId     = $this->filled('branch_id')  ? (int) $this->input('branch_id')  : null;
            $collegeId    = $this->filled('college_id') ? (int) $this->input('college_id') : null;
            $majorId      = $this->filled('major_id')   ? (int) $this->input('major_id')   : null;

            // 1) تأكُّد: الفرع يتبع الجامعة
            if ($branchId) {
                $ok = UniversityBranch::where('id', $branchId)
                    ->where('university_id', $universityId)
                    ->exists();
                if (! $ok) {
                    $v->errors()->add('branch_id', 'الفرع المحدد لا يتبع الجامعة المختارة.');
                }
            }

            // 2) تأكُّد: الكلية تتبع الفرع (إن أُرسل branch_id)،
            //    أو على الأقل تتبع جامعة المحتوى عبر فرعها.
            if ($collegeId) {
                $college = College::find($collegeId);
                if (! $college) {
                    $v->errors()->add('college_id', 'الكلية المحددة غير موجودة.');
                } else {
                    if ($branchId && $college->branch_id !== $branchId) {
                        $v->errors()->add('college_id', 'الكلية لا تتبع الفرع المختار.');
                    } else {
                        // تحقّق غير مباشر عبر الجامعة
                        $belongsToUniversity = UniversityBranch::where('id', $college->branch_id)
                            ->where('university_id', $universityId)
                            ->exists();
                        if (! $belongsToUniversity) {
                            $v->errors()->add('college_id', 'الكلية لا تتبع الجامعة المختارة.');
                        }
                    }
                }
            }

            // 3) تأكُّد: التخصص يتبع الكلية
            if ($majorId) {
                $major = Major::find($majorId);
                if (! $major) {
                    $v->errors()->add('major_id', 'التخصص المحدد غير موجود.');
                } else {
                    if ($collegeId && $major->college_id !== $collegeId) {
                        $v->errors()->add('major_id', 'التخصص لا يتبع الكلية المختارة.');
                    } else {
                        // إن لم تُرسل الكلية، نتحقق من تعلّقها بالجامعة عبر الكلية←الفرع
                        $belongsToUniversity = UniversityBranch::where('id',
                                College::where('id', $major->college_id)->value('branch_id')
                            )->where('university_id', $universityId)->exists();
                        if (! $belongsToUniversity) {
                            $v->errors()->add('major_id', 'التخصص لا يتبع الجامعة المختارة.');
                        }
                    }
                }
            }

            // 4) مواءمة المادة (Material) مع الهرم:
            if ($this->filled('material_id')) {
                $material = Material::find((int)$this->input('material_id'));
                if ($material) {
                    // لو للمادة قيود، يجب أن تُطابق
                    if ($material->university_id && $material->university_id !== $universityId) {
                        $v->errors()->add('material_id', 'المادة لا تتبع الجامعة المختارة.');
                    }
                    if ($branchId && $material->branch_id && $material->branch_id !== $branchId) {
                        $v->errors()->add('material_id', 'المادة لا تتبع الفرع المختار.');
                    }
                    if ($collegeId && $material->college_id && $material->college_id !== $collegeId) {
                        $v->errors()->add('material_id', 'المادة لا تتبع الكلية المختارة.');
                    }
                    if ($majorId && $material->major_id && $material->major_id !== $majorId) {
                        $v->errors()->add('material_id', 'المادة لا تتبع التخصص المختار.');
                    }
                }
            }

            // 5) مواءمة الدكتور (Doctor) مع الهرم (إن وُجدت قيود له):
            if ($this->filled('doctor_id')) {
                $doctor = Doctor::find((int)$this->input('doctor_id'));
                if ($doctor) {
                    if ($doctor->university_id && $doctor->university_id !== $universityId) {
                        $v->errors()->add('doctor_id', 'الدكتور لا يتبع الجامعة المختارة.');
                    }
                    if ($branchId && $doctor->branch_id && $doctor->branch_id !== $branchId) {
                        $v->errors()->add('doctor_id', 'الدكتور لا يتبع الفرع المختار.');
                    }
                    if ($collegeId && $doctor->college_id && $doctor->college_id !== $collegeId) {
                        $v->errors()->add('doctor_id', 'الدكتور لا يتبع الكلية المختارة.');
                    }
                    if ($majorId && $doctor->major_id && $doctor->major_id !== $majorId) {
                        $v->errors()->add('doctor_id', 'الدكتور لا يتبع التخصص المختار.');
                    }
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'title.required' => 'عنوان المحتوى مطلوب.',
            'type.required'  => 'نوع المحتوى مطلوب.',
            'source_url.required_if' => 'رابط المصدر مطلوب عند اختيار نوع فيديو أو رابط.',
            'file.required_if'       => 'الملف مطلوب عند اختيار نوع ملف.',

            'university_id.required' => 'يرجى اختيار الجامعة.',
            'university_id.exists'   => 'الجامعة المحددة غير موجودة.',
            'branch_id.exists'       => 'الفرع المحدد غير موجود.',
            'college_id.exists'      => 'الكلية المحددة غير موجودة.',
            'major_id.exists'        => 'التخصص المحدد غير موجود.',

            'material_id.exists'     => 'المادة المحددة غير موجودة.',
            'doctor_id.exists'       => 'الدكتور المحدد غير موجود.',
        ];
    }
}
