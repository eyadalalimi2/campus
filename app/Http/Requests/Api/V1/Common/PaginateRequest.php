<?php

namespace App\Http\Requests\Api\V1\Common;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read string|null       $cursor
 * @property-read int|string|null   $limit  رقم أو 'all'
 * @property-read string|null       $sort
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
            // limit يمكن أن يكون رقمًا (int أو نص رقمي) أو الكلمة 'all'
            'limit'  => [
                'nullable',
                function (string $attribute, $value, $fail) {
                    if (is_int($value)) {
                        if ($value < 1) $fail('الحد الأدنى للنتائج هو عنصر واحد.');
                        return;
                    }
                    if (is_string($value)) {
                        $lower = strtolower($value);
                        if ($lower === 'all') return; // مقبول
                        if (preg_match('/^\\d+$/', $value)) {
                            if ((int)$value < 1) $fail('الحد الأدنى للنتائج هو عنصر واحد.');
                            return;
                        }
                    }
                    $fail("قيمة الحد يجب أن تكون رقمًا صحيحًا أو الكلمة 'all'.");
                },
            ],
            'sort'   => ['nullable', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'cursor.string' => 'قيمة المؤشر يجب أن تكون نصية.',
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
