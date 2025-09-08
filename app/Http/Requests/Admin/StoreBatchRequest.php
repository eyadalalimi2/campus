<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreBatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('admin')->check();
    }

    public function prepareForValidation(): void
    {
        // توليد اسم تلقائي إذا لم يُرسل
        $name = trim((string)($this->name ?? ''));
        if ($name === '') {
            $name = 'دفعة ' . now()->format('Y-m-d H:i');
        }
        $this->merge([
            'name'        => $name,
            'notes'       => $this->notes ?? '', // منع NULL
            'code_length' => (int)($this->code_length ?: 10), // الافتراضي 10 أرقام
        ]);
    }

    public function rules(): array
    {
        return [
            'name'            => 'required|string|max:150',
            'notes'           => 'nullable|string',
            'plan_id'         => 'required|exists:plans,id',
            'university_id'   => 'nullable|exists:universities,id',
            'college_id'      => 'nullable|exists:colleges,id',
            'major_id'        => 'nullable|exists:majors,id',
            'quantity'        => 'required|integer|min:1|max:20000',
            'status'          => 'nullable|in:draft,active,disabled,archived',
            'duration_days'   => 'required|integer|min:1|max:1825',
            'start_policy'    => 'required|in:on_redeem,fixed_start',
            'starts_on'       => 'nullable|date',
            'valid_from'      => 'nullable|date',
            'valid_until'     => 'nullable|date|after_or_equal:valid_from',
            'code_prefix'     => 'nullable|string|max:24',
            'code_length'     => 'required|integer|min:4|max:24',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($v) {
            if ($this->start_policy === 'fixed_start' && empty($this->starts_on)) {
                $v->errors()->add('starts_on', 'عند اختيار بداية ثابتة يجب تحديد تاريخ البداية.');
            }
        });
    }
}
