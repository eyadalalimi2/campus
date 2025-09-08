<?php

namespace App\Http\Requests\Api\V1\Common;

use Illuminate\Foundation\Http\FormRequest;

class PaginateRequest extends FormRequest
{
    /**
     * إيقاف التحقق عند أول خطأ
     */
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
            'cursor' => $this->input('cursor') !== null ? trim($this->input('cursor')) : null,
            'sort'   => $this->input('sort')   !== null ? trim($this->input('sort'))   : null,
            'limit'  => $this->filled('limit') ? (int) $this->input('limit')          : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'cursor' => ['nullable', 'string'],
            'limit'  => ['nullable', 'integer', 'min:1', 'max:50'],
            'sort'   => ['nullable', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'cursor.string'  => 'قيمة المؤشر يجب أن تكون نصية.',
            'limit.integer'  => 'قيمة الحد يجب أن تكون رقمًا صحيحًا.',
            'limit.min'      => 'الحد الأدنى للنتائج هو عنصر واحد.',
            'limit.max'      => 'الحد الأقصى للنتائج هو 50 عنصرًا.',
            'sort.string'    => 'قيمة الترتيب يجب أن تكون نصية.',
            'sort.max'       => 'قيمة الترتيب تتجاوز الطول المسموح.',
        ];
    }

    public function attributes(): array
    {
        return [
            'cursor' => 'المؤشر',
            'limit'  => 'الحد',
            'sort'   => 'الترتيب',
        ];
    }
}
