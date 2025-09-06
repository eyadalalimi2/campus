<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\College;
use App\Models\Major;

/**
 * @mixin \Illuminate\Http\Request
 */
class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    protected function prepareForValidation(): void
    {
        $data = $this->all();
        foreach (['name','email','phone','student_number','country'] as $f) {
            if (isset($data[$f]) && is_string($data[$f])) $data[$f] = trim($data[$f]);
        }
        if (isset($data['email'])) $data['email'] = strtolower($data['email']);
        $this->merge($data);
    }

    public function rules(): array
    {
        $userId = $this->user()->id;

        return [
            'name'           => ['sometimes','string','max:255'],
            'email'          => ['sometimes','email','max:255', Rule::unique('users','email')->ignore($userId)],
            'phone'          => ['sometimes','nullable','string','max:20', Rule::unique('users','phone')->ignore($userId)],
            'student_number' => ['sometimes','nullable','string','max:50', Rule::unique('users','student_number')->ignore($userId)->whereNotNull('student_number')],
            'country'        => ['sometimes','nullable','string','max:100'],

            'university_id'  => ['sometimes','nullable','integer','exists:universities,id'],
            'college_id'     => ['sometimes','nullable','integer','exists:colleges,id'],
            'major_id'       => ['sometimes','nullable','integer','exists:majors,id'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $universityId = $this->input('university_id');
            $collegeId    = $this->input('college_id');
            $majorId      = $this->input('major_id');

            if ($collegeId && !$universityId) {
                $validator->errors()->add('college_id','يلزم تحديد الجامعة عند تحديد الكلية.');
            }
            if ($majorId && !$collegeId) {
                $validator->errors()->add('major_id','يلزم تحديد الكلية عند تحديد التخصص.');
            }
            if ($universityId && $collegeId) {
                $college = College::find($collegeId);
                if ($college && (int)$college->university_id !== (int)$universityId) {
                    $validator->errors()->add('college_id','هذه الكلية لا تنتمي إلى الجامعة المحددة.');
                }
            }
            if ($collegeId && $majorId) {
                $major = Major::find($majorId);
                if ($major && (int)$major->college_id !== (int)$collegeId) {
                    $validator->errors()->add('major_id','هذا التخصص لا ينتمي إلى الكلية المحددة.');
                }
            }
        });
    }
}
