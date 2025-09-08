<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\DB;

class StoreBatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // اربطه بصلاحيات الأدمن لاحقًا إن لزم
    }

    public function rules(): array
    {
        $id = $this->route('activation_code_batch')?->id ?? null;

        return [
            'name'            => ['required','string','max:150'],
            'notes'           => ['nullable','string'],
            'plan_id'         => ['required','integer','exists:plans,id'],
            'university_id'   => ['nullable','integer','exists:universities,id'],
            'college_id'      => ['nullable','integer','exists:colleges,id'],
            'major_id'        => ['nullable','integer','exists:majors,id'],
            'quantity'        => ['required','integer','min:1','max:1000000'],
            'status'          => ['required', Rule::in(['draft','active','disabled','archived'])],
            'duration_days'   => ['required','integer','min:1','max:65535'],
            'start_policy'    => ['required', Rule::in(['on_redeem','fixed_start'])],
            'starts_on'       => ['nullable','date', 'required_if:start_policy,fixed_start'],
            'valid_from'      => ['nullable','date'],
            'valid_until'     => ['nullable','date','after_or_equal:valid_from'],
            'code_prefix'     => ['nullable','string','max:24'],
            'code_length'     => ['required','integer','min:6','max:64'],
            'created_by_admin_id' => ['nullable','integer','exists:admins,id'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $v) {
            $universityId = $this->input('university_id');
            $collegeId    = $this->input('college_id');
            $majorId      = $this->input('major_id');

            // تحقق مطابق لِمنطق المشغلات (Triggers) في قاعدة البيانات
            if ($collegeId && $universityId) {
                $ok = DB::table('colleges')->where('id',$collegeId)
                    ->where('university_id',$universityId)->exists();
                if (!$ok) {
                    $v->errors()->add('college_id','college_id لا يتبع university_id المحدد.');
                }
            }

            if ($majorId) {
                $query = DB::table('majors as m')
                    ->join('colleges as c','c.id','=','m.college_id')
                    ->where('m.id',$majorId);

                if ($collegeId)    $query->where('m.college_id',$collegeId);
                if ($universityId) $query->where('c.university_id',$universityId);

                if (!$query->exists()) {
                    $v->errors()->add('major_id','major_id لا يتبع الكلية/الجامعة المُدخلين.');
                }
            }

            // طول الكود النهائي يجب أن يستوعب الـprefix
            $prefix = (string) $this->input('code_prefix', '');
            $len    = (int) $this->input('code_length', 14);
            if (mb_strlen($prefix) > 0 && $len < mb_strlen($prefix) + 4) {
                $v->errors()->add('code_length','code_length يجب أن يكون أكبر من طول الـprefix + 4 على الأقل.');
            }
        });
    }
}
