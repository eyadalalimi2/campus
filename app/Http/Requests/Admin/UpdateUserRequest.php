<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\UniversityBranch;
use App\Models\College;
use App\Models\Major;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // أو auth('admin')->check() لو تستخدم حارس الإداريين
    }

    public function rules(): array
    {
        // محاولة جلب المعرّف من روت resource: admin/users/{user}
        $routeUser = $this->route('user');
        $userId = is_object($routeUser) ? $routeUser->id : (int) $routeUser;

        return [
            'is_linked_to_university' => ['nullable', Rule::in(['0','1'])],

            'student_number' => [
                'nullable', 'string', 'max:255',
                Rule::unique('users', 'student_number')->ignore($userId),
            ],
            'name'  => ['nullable','string','max:255'],
            'email' => [
                'required','email','max:255',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'phone'      => ['nullable','string','max:20'],

            // البلد يبقى مطلوبًا
            'country_id' => ['required','exists:countries,id'],

            // التسلسل الهرمي
            'university_id' => ['nullable','exists:universities,id','required_if:is_linked_to_university,1'],
            'branch_id'     => ['nullable','exists:university_branches,id','required_if:is_linked_to_university,1'],
            'college_id'    => ['nullable','exists:colleges,id'],
            'major_id'      => ['nullable','exists:majors,id'],

            'public_college_id' => ['nullable','exists:public_colleges,id'],
            'public_major_id'   => ['nullable','exists:public_majors,id'],
            'email_verified_at' => ['nullable','date'],

            'level'  => ['nullable','integer','min:1'],
            'gender' => ['nullable', Rule::in(['male','female'])],
            'status' => ['nullable', Rule::in(['active','suspended','graduated'])],

            // كلمة المرور اختيارية في التحديث
            'password' => ['nullable','string','min:8','confirmed'],

            // صورة البروفايل (اختياري)
            'profile_photo' => ['nullable','file','mimes:jpg,jpeg,png,webp','max:5120'], // 5MB
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($v) {
            $linked       = $this->input('is_linked_to_university') === '1';
            $universityId = $this->filled('university_id') ? (int)$this->input('university_id') : null;
            $branchId     = $this->filled('branch_id')     ? (int)$this->input('branch_id')     : null;
            $collegeId    = $this->filled('college_id')    ? (int)$this->input('college_id')    : null;
            $majorId      = $this->filled('major_id')      ? (int)$this->input('major_id')      : null;

            // 1) الفرع يجب أن يتبع الجامعة عند الربط المؤسسي
            if ($linked && $universityId && $branchId) {
                $ok = UniversityBranch::where('id', $branchId)
                    ->where('university_id', $universityId)
                    ->exists();
                if (! $ok) {
                    $v->errors()->add('branch_id', 'الفرع المحدد لا يتبع الجامعة المختارة.');
                }
            }

            // 2) الكلية (إن وُجدت) يجب أن تتبع الفرع، أو على الأقل تتبع جامعة المستخدم عبر فرعها
            if ($collegeId) {
                $branchOfCollege = College::whereKey($collegeId)->value('branch_id');
                if (! $branchOfCollege) {
                    $v->errors()->add('college_id', 'الكلية المحددة غير موجودة.');
                } else {
                    if ($branchId && (int)$branchOfCollege !== (int)$branchId) {
                        $v->errors()->add('college_id', 'الكلية لا تتبع الفرع المختار.');
                    } elseif ($linked && $universityId) {
                        $belongsToUni = UniversityBranch::where('id', $branchOfCollege)
                            ->where('university_id', $universityId)
                            ->exists();
                        if (! $belongsToUni) {
                            $v->errors()->add('college_id', 'الكلية لا تتبع الجامعة المختارة.');
                        }
                    }
                }
            }

            // 3) التخصص (إن وُجد) يجب أن يتبع الكلية
            if ($majorId) {
                $collegeOfMajor = Major::whereKey($majorId)->value('college_id');
                if (! $collegeOfMajor) {
                    $v->errors()->add('major_id', 'التخصص المحدد غير موجود.');
                } else {
                    if ($collegeId && (int)$collegeOfMajor !== (int)$collegeId) {
                        $v->errors()->add('major_id', 'التخصص لا يتبع الكلية المختارة.');
                    } elseif ($linked && $universityId) {
                        $branchOfCollege = College::whereKey($collegeOfMajor)->value('branch_id');
                        $belongsToUni = UniversityBranch::where('id', $branchOfCollege)
                            ->where('university_id', $universityId)
                            ->exists();
                        if (! $belongsToUni) {
                            $v->errors()->add('major_id', 'التخصص لا يتبع الجامعة المختارة.');
                        }
                    }
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'email.required' => 'البريد الإلكتروني مطلوب.',
            'email.email'    => 'صيغة البريد الإلكتروني غير صحيحة.',
            'email.unique'   => 'هذا البريد مستخدم مسبقًا.',

            'country_id.required' => 'يرجى اختيار الدولة.',
            'country_id.exists'   => 'الدولة المحددة غير موجودة.',

            'university_id.required_if' => 'اختيار الجامعة مطلوب عند ربط الطالب بجامعة.',
            'university_id.exists'      => 'الجامعة المحددة غير موجودة.',
            'branch_id.required_if'     => 'اختيار الفرع مطلوب عند ربط الطالب بجامعة.',
            'branch_id.exists'          => 'الفرع المحدد غير موجود.',
            'college_id.exists'         => 'الكلية المحددة غير موجودة.',
            'major_id.exists'           => 'التخصص المحدد غير موجود.',

            'password.min'       => 'كلمة المرور يجب ألا تقل عن 8 أحرف.',
            'password.confirmed' => 'تأكيد كلمة المرور غير مطابق.',
            'profile_photo.mimes' => 'صيغة صورة البروفايل يجب أن تكون JPG أو PNG أو WEBP.',
            'profile_photo.max'   => 'حجم صورة البروفايل يجب ألا يتجاوز 5MB.',
        ];
    }
}
