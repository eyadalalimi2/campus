<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePlanFeatureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('admin')->check();
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'feature_key'   => is_string($this->feature_key) ? trim($this->feature_key) : $this->feature_key,
            'feature_value' => is_string($this->feature_value) ? trim($this->feature_value) : $this->feature_value,
        ]);
    }

    public function rules(): array
    {
        // الحصول على plan_id من المسار المتداخل
        $planParam = $this->route('plan');
        $planId = is_object($planParam) ? ($planParam->id ?? null) : $planParam;

        return [
            'feature_key'   => [
                'required','string','max:100',
                // جعل المفتاح فريدًا ضمن نفس الخطة
                Rule::unique('plan_features','feature_key')->where(fn($q)=>$q->where('plan_id',$planId))
            ],
            'feature_value' => ['nullable','string'],
        ];
    }

    public function messages(): array
    {
        return [
            'feature_key.required' => 'المفتاح مطلوب.',
            'feature_key.unique'   => 'هذا المفتاح موجود بالفعل لهذه الخطة.',
        ];
    }
}
