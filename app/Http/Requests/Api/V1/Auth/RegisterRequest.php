<?php

namespace App\Http\Requests\Api\V1\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

/**
 * @property-read string      $name
 * @property-read string      $email
 * @property-read string      $password
 * @property-read string      $password_confirmation
 * @property-read string|null $phone
 * @property-read int         $country_id
 * @property-read int|null    $university_id
 * @property-read int|null    $college_id
 * @property-read int|null    $major_id
 * @property-read int|null    $level
 * @property-read string|null $gender
 * @property-read string      $login_device
 */
final class RegisterRequest extends FormRequest
{
    /**
     * تقليل زمن الاستجابة بإيقاف التحقق عند أول خطأ.
     */
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    /**
     * قواعد التحقق القياسية.
     * ملاحظة: التطبيع (lowercase للبريد..إلخ) يُدار داخل الـModel Mutators.
     */
    public function rules(): array
    {
        return [
            'name'                   => ['required', 'string', 'min:2', 'max:255'],
            'email'                  => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'               => ['required', 'string', 'min:6', 'max:100', 'confirmed'],
            'password_confirmation'  => ['required', 'string', 'min:6', 'max:100'],

            'phone'         => ['nullable', 'string', 'max:20'],
            'country_id'    => ['required', 'integer', 'exists:countries,id'],
            'university_id' => ['nullable', 'integer', 'exists:universities,id'],
            'college_id'    => ['nullable', 'integer', 'exists:colleges,id'],
            'major_id'      => ['nullable', 'integer', 'exists:majors,id'],
            'level'         => ['nullable', 'integer', 'min:0', 'max:50'],
            'gender'        => ['nullable', 'in:male,female'],

            // اسم جهاز تسجيل الدخول لتوليد توكن Sanctum
            'login_device'  => ['required', 'string', 'max:60'],
        ];
    }

    /**
     * فحوصات علاقة الهيكل (بعد التحقق القياسي).
     * لا نستخدم input()/filled()/merge()؛ نعتمد على البيانات الـvalidated.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($v) {
            // الحصول على القيم التي مرّت من التحقق القياسي
            $data = method_exists($v, 'validated') ? (array) $v->validated() : [];

            $universityId = $data['university_id'] ?? null;
            $collegeId    = $data['college_id']    ?? null;
            $majorId      = $data['major_id']      ?? null;

            // تحقق: الكلية تتبع الجامعة (إن وُجدا)
            if ($collegeId !== null && $universityId !== null) {
                $ok = DB::table('colleges')
                    ->where('id', (int) $collegeId)
                    ->where('university_id', (int) $universityId)
                    ->exists();

                if (!$ok) {
                    $v->errors()->add('college_id', 'الكلية لا تتبع الجامعة المحددة.');
                }
            }

            // تحقق: التخصص يتبع الكلية/الجامعة (إن وُجد)
            if ($majorId !== null) {
                $q = DB::table('majors as m')
                    ->join('colleges as c', 'c.id', '=', 'm.college_id')
                    ->where('m.id', (int) $majorId);

                if ($collegeId !== null) {
                    $q->where('m.college_id', (int) $collegeId);
                }
                if ($universityId !== null) {
                    $q->where('c.university_id', (int) $universityId);
                }

                if (!$q->exists()) {
                    $v->errors()->add('major_id', 'التخصص لا يتبع الكلية/الجامعة المحددة.');
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'name.required'      => 'الاسم مطلوب.',
            'name.min'           => 'الاسم قصير جدًا.',
            'name.max'           => 'الاسم يتجاوز الحد المسموح.',
            'email.required'     => 'البريد الإلكتروني مطلوب.',
            'email.email'        => 'صيغة البريد غير صحيحة.',
            'email.max'          => 'البريد يتجاوز 255 حرفًا.',
            'email.unique'       => 'البريد مستخدم مسبقًا.',
            'password.required'  => 'كلمة المرور مطلوبة.',
            'password.min'       => 'كلمة المرور يجب ألا تقل عن 6 أحرف.',
            'password.max'       => 'كلمة المرور تتجاوز الحد المسموح.',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق.',
            'password_confirmation.required' => 'حقل تأكيد كلمة المرور مطلوب.',

            'country_id.required' => 'الدولة مطلوبة.',
            'country_id.exists'   => 'الدولة غير صحيحة.',
            'university_id.exists'=> 'الجامعة غير صحيحة.',
            'college_id.exists'   => 'الكلية غير صحيحة.',
            'major_id.exists'     => 'التخصص غير صحيح.',
            'gender.in'           => 'قيمة الجنس يجب أن تكون male أو female.',

            'login_device.required' => 'اسم جهاز تسجيل الدخول مطلوب.',
            'login_device.max'      => 'اسم جهاز تسجيل الدخول يتجاوز 60 حرفًا.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name'                   => 'الاسم',
            'email'                  => 'البريد الإلكتروني',
            'password'               => 'كلمة المرور',
            'password_confirmation'  => 'تأكيد كلمة المرور',
            'phone'                  => 'الهاتف',
            'country_id'             => 'الدولة',
            'university_id'          => 'الجامعة',
            'college_id'             => 'الكلية',
            'major_id'               => 'التخصص',
            'level'                  => 'المستوى',
            'gender'                 => 'الجنس',
            'login_device'           => 'اسم جهاز تسجيل الدخول',
        ];
    }

    /**
     * مُلخّص القيم المُتحقَّق منها لاستخدامه في الكنترولر.
     */
    public function data(): array
    {
        return $this->validated();
    }
}
