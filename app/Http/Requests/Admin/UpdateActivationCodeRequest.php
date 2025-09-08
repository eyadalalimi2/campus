<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class UpdateActivationCodeRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $id = $this->route('activation_code')?->id ?? null;

        return [
            'batch_id'        => ['nullable','integer','exists:activation_code_batches,id'],
            'code'            => ['required','string','max:64', Rule::unique('activation_codes','code')->ignore($id)],
            'plan_id'         => ['required','integer','exists:plans,id'],
            'university_id'   => ['nullable','integer','exists:universities,id'],
            'college_id'      => ['nullable','integer','exists:colleges,id'],
            'major_id'        => ['nullable','integer','exists:majors,id'],
            'duration_days'   => ['required','integer','min:1','max:65535'],
            'start_policy'    => ['required', Rule::in(['on_redeem','fixed_start'])],
            'starts_on'       => ['nullable','date','required_if:start_policy,fixed_start'],
            'valid_from'      => ['nullable','date'],
            'valid_until'     => ['nullable','date','after_or_equal:valid_from'],
            'max_redemptions' => ['required','integer','min:1','max:255'],
            'status'          => ['required', Rule::in(['active','redeemed','expired','disabled'])],
            'redeemed_by_user_id' => ['nullable','integer','exists:users,id'],
            'redeemed_at'     => ['nullable','date'],
            'created_by_admin_id' => ['nullable','integer','exists:admins,id'],
        ];
    }

    public function withValidator(\Illuminate\Validation\Validator $validator): void
    {
        $validator->after(function ($v) {
            $universityId = $this->input('university_id');
            $collegeId    = $this->input('college_id');
            $majorId      = $this->input('major_id');

            if ($collegeId && $universityId) {
                $ok = DB::table('colleges')->where('id',$collegeId)
                    ->where('university_id',$universityId)->exists();
                if (!$ok) $v->errors()->add('college_id','college_id لا يتبع university_id.');
            }

            if ($majorId) {
                $query = DB::table('majors as m')
                    ->join('colleges as c','c.id','=','m.college_id')
                    ->where('m.id',$majorId);

                if ($collegeId)    $query->where('m.college_id',$collegeId);
                if ($universityId) $query->where('c.university_id',$universityId);

                if (!$query->exists()) {
                    $v->errors()->add('major_id','major_id لا يتبع الكلية/الجامعة.');
                }
            }
        });
    }
}
