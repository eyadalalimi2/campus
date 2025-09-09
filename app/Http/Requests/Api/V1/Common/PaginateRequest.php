<?php

namespace App\Http\Requests\Api\V1\Common;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read string|null $cursor
 * @property-read int|null    $limit
 * @property-read string|null $sort
 */
final class PaginateRequest extends FormRequest
{
    /**
     * إيقاف التحقق عند أول خطأ لتقليل زمن الاستجابة.
     */
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
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
            'cursor.string' => 'قيمة المؤشر يجب أن تكون نصية.',
            'limit.integer' => 'قيمة الحد يجب أن تكون رقمًا صحيحًا.',
            'limit.min'     => 'الحد الأدنى للنتائج هو عنصر واحد.',
            'limit.max'     => 'الحد الأقصى للنتائج هو 50 عنصرًا.',
            'sort.string'   => 'قيمة الترتيب يجب أن تكون نصية.',
            'sort.max'      => 'قيمة الترتيب تتجاوز الطول المسموح.',
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

    /**
     * القيم المُتحقَّق منها لاستخدامها في الكنترولر.
     */
    public function data(): array
    {
        return $this->validated();
    }
}
