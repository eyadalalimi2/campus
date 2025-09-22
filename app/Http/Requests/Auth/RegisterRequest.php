<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\UniversityBranch;
use App\Models\College;
use App\Models\Major;

/**
 * @mixin \Illuminate\Http\Request
 */
class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $data = $this->all();

        foreach (['name','email','phone','student_number','country'] as $field) {
            if (isset($data[$field]) && is_string($data[$field])) {
                $data[$field] = trim($data[$field]);
            }
        }

        if (isset($data['email']) && is_string($data['email'])) {
            $data['email'] = strtolower($data['email']);
        }

        $this->merge($data);
    }

    public function rules(): array
    {
        return [
            'name'           => ['required','string','max:255'],
            'email'          => ['required','email','max:255','unique:users,email'],
            'phone'          => ['nullable','string','max:20'],
            'student_number' => ['nullable','string','max:50', Rule::unique('users','student_number')->whereNotNull('student_number')],
            'country'        => ['nullable','string','max:100'],

            'password'       => ['required','confirmed','min:8'],

            // السلسلة المؤسسية (اختيارية؛ لكن كل مستوى يُلزم المستوى الأعلى عند إرساله)
            'university_id'  => ['nullable','integer','exists:universities,id'],
            'branch_id'      => ['nullable','integer','exists:university_branches,id'],
            'college_id'     => ['nullable','integer','exists:colleges,id'],
            'major_id'       => ['nullable','integer','exists:majors,id'],
        ];
    }

    /**
     * تحقق هرمي:
     * - إن وُجد branch_id يجب وجود university_id، ويجب أن ينتمي الفرع إلى نفس الجامعة.
     * - إن وُجد college_id يجب وجود branch_id، ويجب أن تنتمي الكلية إلى نفس الفرع.
     * - إن وُجد major_id يجب وجود college_id، ويجب أن ينتمي التخصص إلى نفس الكلية.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            /** @var \Illuminate\Contracts\Validation\Validator $validator */

            $universityId = $this->input('university_id');
            $branchId     = $this->input('branch_id');
            $collegeId    = $this->input('college_id');
            $majorId      = $this->input('major_id');

            // مستوى الفرع يتطلب الجامعة
            if ($branchId && !$universityId) {
                $validator->errors()->add('branch_id', 'يلزم تحديد الجامعة عند تحديد الفرع.');
            }

            // مستوى الكلية يتطلب الفرع
            if ($collegeId && !$branchId) {
                $validator->errors()->add('college_id', 'يلزم تحديد الفرع عند تحديد الكلية.');
            }

            // مستوى التخصص يتطلب الكلية
            if ($majorId && !$collegeId) {
                $validator->errors()->add('major_id', 'يلزم تحديد الكلية عند تحديد التخصص.');
            }

            // مطابقة الفرع مع الجامعة
            if ($universityId && $branchId) {
                $branch = UniversityBranch::find($branchId);
                if ($branch && (int)$branch->university_id !== (int)$universityId) {
                    $validator->errors()->add('branch_id', 'هذا الفرع لا ينتمي إلى الجامعة المحددة.');
                }
            }

            // مطابقة الكلية مع الفرع (لاحظ: الكليات تتبع الفروع)
            if ($branchId && $collegeId) {
                $college = College::find($collegeId);
                if ($college && (int)$college->branch_id !== (int)$branchId) {
                    $validator->errors()->add('college_id', 'هذه الكلية لا تنتمي إلى الفرع المحدد.');
                }
            }

            // مطابقة التخصص مع الكلية
            if ($collegeId && $majorId) {
                $major = Major::find($majorId);
                if ($major && (int)$major->college_id !== (int)$collegeId) {
                    $validator->errors()->add('major_id', 'هذا التخصص لا ينتمي إلى الكلية المحددة.');
                }
            }
        });
    }
}
