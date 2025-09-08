<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class UpdateActivationCodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('admin')->check();
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'notes' => $this->notes ?? '',
        ]);
    }

    public function rules(): array
    {
        $id = $this->route('code')?->id ?? $this->route('activation_code');
        return [
            'batch_id'        => 'nullable|exists:activation_code_batches,id',
            'code'            => ['required','string','max:64', Rule::unique('activation_codes','code')->ignore($id)],
            'plan_id'         => 'required|exists:plans,id',
            'university_id'   => 'nullable|exists:universities,id',
            'college_id'      => 'nullable|exists:colleges,id',
            'major_id'        => 'nullable|exists:majors,id',
            'duration_days'   => 'required|integer|min:1|max:1825',
            'start_policy'    => 'required|in:on_redeem,fixed_start',
            'starts_on'       => 'nullable|date',
            'valid_from'      => 'nullable|date',
            'valid_until'     => 'nullable|date|after_or_equal:valid_from',
            'max_redemptions' => 'required|integer|min:1|max:1000',
            'status'          => 'required|in:active,redeemed,expired,disabled',
            'notes'           => 'nullable|string',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($v) {
            if ($this->start_policy === 'fixed_start' && empty($this->starts_on)) {
                $v->errors()->add('starts_on','عند اختيار بداية ثابتة يجب تحديد تاريخ البداية.');
            }
            $u = $this->university_id;
            $c = $this->college_id;
            $m = $this->major_id;

            if ($c && $u) {
                $ok = DB::table('colleges')->where('id',$c)->where('university_id',$u)->exists();
                if (!$ok) $v->errors()->add('college_id','الكلية لا تتبع هذه الجامعة.');
            }
            if ($m) {
                $row = DB::table('majors')
                    ->join('colleges','colleges.id','=','majors.college_id')
                    ->where('majors.id',$m)
                    ->first(['majors.college_id','colleges.university_id']);
                if (!$row) {
                    $v->errors()->add('major_id','التخصص غير موجود.');
                } else {
                    if ($c && (int)$row->college_id !== (int)$c) {
                        $v->errors()->add('major_id','التخصص لا يتبع الكلية المحددة.');
                    }
                    if ($u && (int)$row->university_id !== (int)$u) {
                        $v->errors()->add('major_id','التخصص لا يتبع الجامعة المحددة.');
                    }
                }
            }
        });
    }
}
