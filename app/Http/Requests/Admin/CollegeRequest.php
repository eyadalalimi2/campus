<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\UniversityBranch;

class CollegeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // يمكنك وضع منطق الصلاحيات لاحقًا
    }

    public function rules(): array
    {
        return [
            'university_id' => ['required', 'exists:universities,id'],
            'branch_id'     => ['required', 'exists:university_branches,id'],
            'name'          => ['required', 'string', 'max:255'],
            'is_active'     => ['nullable', 'boolean'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($v) {
            $universityId = (int) $this->input('university_id');
            $branchId     = (int) $this->input('branch_id');

            if ($universityId && $branchId) {
                $belongs = UniversityBranch::where('id', $branchId)
                    ->where('university_id', $universityId)
                    ->exists();

                if (! $belongs) {
                    $v->errors()->add('branch_id', 'الفرع المحدد لا يتبع الجامعة المختارة.');
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'university_id.required' => 'يرجى اختيار الجامعة.',
            'university_id.exists'   => 'الجامعة المحددة غير موجودة.',
            'branch_id.required'     => 'يرجى اختيار الفرع.',
            'branch_id.exists'       => 'الفرع المحدد غير موجود.',
            'name.required'          => 'اسم الكلية مطلوب.',
        ];
    }
}
