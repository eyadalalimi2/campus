<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class MaterialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('admin')->check();
    }

    public function rules(): array
    {
        return [
            'name'  => ['required','string','max:200'],

            // النطاق
            'scope' => ['required', Rule::in(['global','university'])],

            // مفاتيح النطاق الخاص
            'university_id' => ['nullable','required_if:scope,university','integer','exists:universities,id'],
                'branch_id'     => ['nullable','required_if:scope,university','integer','exists:university_branches,id'],
            // التحقق الأساسي، والتحقق العميق سيتم في withValidator
            'college_id'    => ['nullable','integer','exists:colleges,id'],
            'major_id'      => ['nullable','integer','exists:majors,id'],

            // مستوى المادة
            'level' => ['nullable','integer','min:1','max:20'],

            // ✅ الفصول الأكاديمية الجديدة (بدلاً من term)
            'term_ids'      => ['sometimes','array'],
            'term_ids.*'    => ['integer','exists:academic_terms,id'],

            'is_active'     => ['sometimes','boolean'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($v) {
            $scope        = $this->get('scope');
            $universityId = $this->get('university_id');
            $branchId     = $this->get('branch_id');
            $collegeId    = $this->get('college_id');
            $majorId      = $this->get('major_id');

            // تحقق متسلسل عند النطاق الخاص
            if ($scope === 'university') {
                    // 0) الفرع يجب أن يتبع الجامعة
                    if ($branchId) {
                        $ok = DB::table('university_branches')
                            ->where('id', $branchId)
                            ->where('university_id', $universityId)
                            ->exists();
                        if (!$ok) {
                            $v->errors()->add('branch_id', 'الفرع لا يتبع الجامعة المحددة.');
                        }
                    }
                // 1) الكلية (إن وُجدت) يجب أن تتبع الجامعة المختارة
                if ($collegeId) {
                        $ok = DB::table('colleges')
                            ->where('id', $collegeId)
                            ->where('branch_id', $branchId)
                            ->exists();
                        if (!$ok) {
                            $v->errors()->add('college_id', 'الكلية لا تتبع الفرع المحدد.');
                        }
                }

                // 2) التخصص (إن وُجد) يجب أن يتبع الكلية/الجامعة
                if ($majorId) {
                    $major = DB::table('majors')->where('id', $majorId)->first();
                    if (!$major) {
                        $v->errors()->add('major_id', 'التخصص غير موجود.');
                    } else {
                        // إن تم اختيار كلية، يجب أن يطابقها تخصص المادة
                        if ($collegeId && (int)$major->college_id !== (int)$collegeId) {
                            $v->errors()->add('major_id', 'التخصص لا يتبع الكلية المحددة.');
                        } else {
                            // بدون كلية: نتحقق على مستوى الجامعة عبر join مع الكليات
                                $ok = DB::table('majors as m')
                                    ->join('colleges as c', 'c.id', '=', 'm.college_id')
                                    ->where('m.id', $majorId)
                                    ->where('c.branch_id', $branchId)
                                    ->exists();
                                if (!$ok) {
                                    $v->errors()->add('major_id', 'التخصص لا يتبع الفرع المحدد.');
                                }
                        }
                    }
                }
            }
        });
    }
}
