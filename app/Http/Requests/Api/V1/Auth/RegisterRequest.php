<?php

namespace App\Http\Requests\Api\V1\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class RegisterRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    /**
     * تطبيع المدخلات قبل التحقق
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'email'        => ($e = $this->input('email'))        !== null ? mb_strtolower(trim($e)) : $e,
            'name'         => ($n = $this->input('name'))         !== null ? trim($n)               : $n,
            'phone'        => ($p = $this->input('phone'))        !== null ? trim($p)               : $p,
            'gender'       => ($g = $this->input('gender'))       !== null ? trim($g)               : $g,
            'login_device' => ($d = $this->input('login_device')) !== null ? trim($d)               : $d,

            'country_id'    => $this->filled('country_id')    ? (int) $this->input('country_id')    : null,
            'university_id' => $this->filled('university_id') ? (int) $this->input('university_id') : null,
            'college_id'    => $this->filled('college_id')    ? (int) $this->input('college_id')    : null,
            'major_id'      => $this->filled('major_id')      ? (int) $this->input('major_id')      : null,
            'level'         => $this->filled('level')         ? (int) $this->input('level')         : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'name'          => ['required','string','min:2','max:255'],
            'email'         => ['required','string','email','max:255','unique:users,email'],
            'password'      => ['required','string','min:6','max:100','confirmed'],
            'password_confirmation' => ['required','string','min:6','max:100'],
            'phone'         => ['nullable','string','max:20'],
            'country_id'    => ['required','integer','exists:countries,id'],
            'university_id' => ['nullable','integer','exists:universities,id'],
            'college_id'    => ['nullable','integer','exists:colleges,id'],
            'major_id'      => ['nullable','integer','exists:majors,id'],
            'level'         => ['nullable','integer','min:0','max:50'],
            'gender'        => ['nullable','in:male,female'],

            // اسم جهاز تسجيل الدخول (Sanctum Token)
            'login_device'  => ['required','string','max:60'],
        ];
    }

    /**
     * توثيق العلاقات: الكلية ⟵ الجامعة، التخصص ⟵ الكلية/الجامعة
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($v) {
            $universityId = $this->filled('university_id') ? (int) $this->input('university_id') : null;
            $collegeId    = $this->filled('college_id')    ? (int) $this->input('college_id')    : null;
            $majorId      = $this->filled('major_id')      ? (int) $this->input('major_id')      : null;

            // تحقق: الكلية تتبع الجامعة
            if ($collegeId && $universityId) {
                $ok = DB::table('colleges')
                    ->where('id', $collegeId)
                    ->where('university_id', $universityId)
                    ->exists();
                if (!$ok) {
                    $v->errors()->add('college_id', 'الكلية لا تتبع الجامعة المحددة.');
                }
            }

            // تحقق: التخصص يتبع الكلية (ومن خلالها الجامعة إن وُجدت)
            if ($majorId) {
                $q = DB::table('majors as m')
                    ->join('colleges as c', 'c.id', '=', 'm.college_id')
                    ->where('m.id', $majorId);

                if ($collegeId)    { $q->where('m.college_id', $collegeId); }
                if ($universityId) { $q->where('c.university_id', $universityId); }

                if (!$q->exists()) {
                    $v->errors()->add('major_id', 'التخصص لا يتبع الكلية/الجامعة المحددة.');
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'name.required'     => 'الاسم مطلوب.',
            'name.min'          => 'الاسم قصير جدًا.',
            'name.max'          => 'الاسم يتجاوز الحد المسموح.',
            'email.required'    => 'البريد الإلكتروني مطلوب.',
            'email.email'       => 'صيغة البريد غير صحيحة.',
            'email.max'         => 'البريد يتجاوز 255 حرفًا.',
            'email.unique'      => 'البريد مستخدم مسبقًا.',
            'password.required' => 'كلمة المرور مطلوبة.',
            'password.min'      => 'كلمة المرور يجب ألا تقل عن 6 أحرف.',
            'password.max'      => 'كلمة المرور تتجاوز الحد المسموح.',
            'password.confirmed'=> 'تأكيد كلمة المرور غير متطابق.',
            'password_confirmation.required' => 'حقل تأكيد كلمة المرور مطلوب.',
            'country_id.required' => 'الدولة مطلوبة.',
            'country_id.exists'   => 'الدولة غير صحيحة.',
            'university_id.exists'=> 'الجامعة غير صحيحة.',
            'college_id.exists'   => 'الكلية غير صحيحة.',
            'major_id.exists'     => 'التخصص غير صحيح.',
            'gender.in'           => 'قيمة الجنس يجب أن تكون male أو female.',
            'login_device.required'=> 'اسم جهاز تسجيل الدخول مطلوب.',
            'login_device.max'     => 'اسم جهاز تسجيل الدخول يتجاوز 60 حرفًا.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name'             => 'الاسم',
            'email'            => 'البريد الإلكتروني',
            'password'         => 'كلمة المرور',
            'password_confirmation' => 'تأكيد كلمة المرور',
            'phone'            => 'الهاتف',
            'country_id'       => 'الدولة',
            'university_id'    => 'الجامعة',
            'college_id'       => 'الكلية',
            'major_id'         => 'التخصص',
            'level'            => 'المستوى',
            'gender'           => 'الجنس',
            'login_device'     => 'اسم جهاز تسجيل الدخول',
        ];
    }
}
