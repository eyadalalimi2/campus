<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
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

        if (isset($data['email'])) {
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

            'university_id'  => ['nullable','integer','exists:universities,id'],
            'college_id'     => ['nullable','integer','exists:colleges,id'],
            'major_id'       => ['nullable','integer','exists:majors,id'],
        ];
    }

    /**
     * توحيد التحقق عبر الحقول (انتماء الكلية للجامعة، وانتماء التخصص للكلية)
     * بطريقة مفهومة للأدوات (Intelephense).
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            /** @var \Illuminate\Contracts\Validation\Validator $validator */

            $universityId = $this->input('university_id');
            $collegeId    = $this->input('college_id');
            $majorId      = $this->input('major_id');

            if ($collegeId && !$universityId) {
                $validator->errors()->add('college_id', 'يلزم تحديد الجامعة عند تحديد الكلية.');
            }

            if ($majorId && !$collegeId) {
                $validator->errors()->add('major_id', 'يلزم تحديد الكلية عند تحديد التخصص.');
            }

            if ($universityId && $collegeId) {
                $college = College::find($collegeId);
                if ($college && (int)$college->university_id !== (int)$universityId) {
                    $validator->errors()->add('college_id', 'هذه الكلية لا تنتمي إلى الجامعة المحددة.');
                }
            }

            if ($collegeId && $majorId) {
                $major = Major::find($majorId);
                if ($major && (int)$major->college_id !== (int)$collegeId) {
                    $validator->errors()->add('major_id', 'هذا التخصص لا ينتمي إلى الكلية المحددة.');
                }
            }
        });
    }
}
