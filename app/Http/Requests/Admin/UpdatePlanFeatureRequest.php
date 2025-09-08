<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePlanFeatureRequest extends FormRequest
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
        $planParam = $this->route('plan');
        $planId    = is_object($planParam) ? ($planParam->id ?? null) : $planParam;

        $featureParam = $this->route('feature');
        $featureId    = is_object($featureParam) ? ($featureParam->id ?? null) : $featureParam;

        return [
            'feature_key'   => [
                'required','string','max:100',
                Rule::unique('plan_features','feature_key')
                    ->where(fn($q)=>$q->where('plan_id',$planId))
                    ->ignore($featureId)
            ],
            'feature_value' => ['nullable','string'],
        ];
    }
}
