<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\AcademicCalendar;

class AcademicTermRequest extends FormRequest
{
    /**
     * السماح بتنفيذ الطلب للأدمن.
     */
    public function authorize(): bool
    {
        return auth('admin')->check();
    }

    /**
     * تهيئة القيم قبل التحقق.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'calendar_id' => $this->input('calendar_id'),
            'name'        => is_string($this->input('name')) ? strtolower(trim($this->input('name'))) : $this->input('name'),
            'is_active'   => $this->boolean('is_active'),
        ]);
    }

    /**
     * قواعد التحقق بناءً على بنية الجدول:
     * academic_terms: calendar_id, name(enum: first|second|summer), starts_on, ends_on, is_active
     */
    public function rules(): array
    {
        // دعم التحديث عند وجود Route Model Binding: academic_term
        $routeModelOrId = $this->route('academic_term') ?? $this->route('id');
        $currentId = is_object($routeModelOrId) ? ($routeModelOrId->id ?? null) : $routeModelOrId;

        return [
            'calendar_id' => ['required', 'integer', 'exists:academic_calendars,id'],

            // الاسم ضمن القيم المسموحة + فريد داخل نفس التقويم
            'name' => [
                'required',
                Rule::in(['first', 'second', 'summer']),
                Rule::unique('academic_terms', 'name')
                    ->where(fn ($q) => $q->where('calendar_id', (int) $this->input('calendar_id')))
                    ->ignore($currentId),
            ],

            'starts_on' => ['required', 'date'],
            'ends_on'   => ['required', 'date', 'after_or_equal:starts_on'],

            'is_active' => ['nullable', 'boolean'],
        ];
    }

    /**
     * تحققات إضافية بعد قواعد Laravel الأساسية:
     * - نطاق التواريخ داخل نطاق التقويم الأكاديمي المحدد.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $calId = (int) $this->input('calendar_id');
            $start = $this->input('starts_on');
            $end   = $this->input('ends_on');

            if (!$calId || !$start || !$end) {
                return;
            }

            $calendar = AcademicCalendar::find($calId);
            if (!$calendar) {
                return;
            }

            // تأكد أن فترة الفصل ضمن فترة التقويم
            if ($calendar->starts_on && strtotime($start) < strtotime($calendar->starts_on)) {
                $validator->errors()->add('starts_on', 'تاريخ بداية الفصل يجب أن يكون بعد أو يساوي بداية التقويم الأكاديمي.');
            }
            if ($calendar->ends_on && strtotime($end) > strtotime($calendar->ends_on)) {
                $validator->errors()->add('ends_on', 'تاريخ نهاية الفصل يجب أن يكون قبل أو يساوي نهاية التقويم الأكاديمي.');
            }
        });
    }

    /**
     * الرسائل المخصصة.
     */
    public function messages(): array
    {
        return [
            'calendar_id.required' => 'يرجى اختيار التقويم الأكاديمي.',
            'calendar_id.exists'   => 'التقويم الأكاديمي المحدد غير موجود.',

            'name.required'        => 'اسم الفصل مطلوب.',
            'name.in'              => 'اسم الفصل يجب أن يكون أحد القيم: الأول/الثاني/الصيفي.',
            'name.unique'          => 'هذا الفصل موجود مسبقًا لنفس التقويم الأكاديمي.',

            'starts_on.required'   => 'تاريخ بداية الفصل مطلوب.',
            'starts_on.date'       => 'تاريخ بداية الفصل غير صالح.',

            'ends_on.required'     => 'تاريخ نهاية الفصل مطلوب.',
            'ends_on.date'         => 'تاريخ نهاية الفصل غير صالح.',
            'ends_on.after_or_equal' => 'تاريخ النهاية يجب أن يكون بعد أو يساوي تاريخ البداية.',

            'is_active.boolean'    => 'قيمة التفعيل غير صحيحة.',
        ];
    }

    /**
     * أسماء الحقول الودية.
     */
    public function attributes(): array
    {
        return [
            'calendar_id' => 'التقويم الأكاديمي',
            'name'        => 'اسم الفصل',
            'starts_on'   => 'تاريخ البداية',
            'ends_on'     => 'تاريخ النهاية',
            'is_active'   => 'مفعل',
        ];
    }
}
