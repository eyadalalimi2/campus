<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AcademicTermRequest extends FormRequest
{
    /**
     * السماح بتنفيذ الطلب (ضع سياساتك إن لزم).
     */
    public function authorize(): bool
    {
        // عدّل التفويض حسب سياساتك (Gate/Policy). الإرجاع true لتبسيط الاستخدام.
        return true;
    }

    /**
     * تهيئة القيم قبل التحقق.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'name'              => is_string($this->name) ? trim($this->name) : $this->name,
            'code'              => is_string($this->code) ? trim($this->code) : $this->code,
            'status'            => is_string($this->status) ? strtolower(trim($this->status)) : $this->status,
            'is_active'         => filter_var($this->is_active, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
            'academic_year_id'  => $this->academic_year_id ?: null,
        ]);
    }

    /**
     * قواعد التحقق.
     */
    public function rules(): array
    {
        // يدعم التحديث عبر بارامتر route: academic_term أو id (رقم أو نموذج).
        $routeModelOrId = $this->route('academic_term') ?? $this->route('id');
        $currentId = is_object($routeModelOrId) ? ($routeModelOrId->id ?? null) : $routeModelOrId;

        // جدول الفصول الأكاديمية المتوقع
        $table = 'academic_terms';

        $nameUnique = Rule::unique($table, 'name')->where(function ($q) {
            // في حال لديك soft deletes
            if (schema()->hasColumn('academic_terms', 'deleted_at')) {
                $q->whereNull('deleted_at');
            }
            return $q;
        })->ignore($currentId);

        $codeUnique = Rule::unique($table, 'code')->where(function ($q) {
            if (schema()->hasColumn('academic_terms', 'deleted_at')) {
                $q->whereNull('deleted_at');
            }
            return $q;
        })->ignore($currentId);

        // حالات الحالة (عدّل القائمة لملاءمة نطاق عملك)
        $statusIn = Rule::in(['planned', 'active', 'completed', 'archived']);

        $common = [
            'name'              => ['required', 'string', 'min:2', 'max:100', $nameUnique],
            'code'              => ['required', 'string', 'min:2', 'max:20', $codeUnique],
            'academic_year_id'  => ['nullable', 'integer', 'exists:academic_years,id'],
            'start_date'        => ['required', 'date'],
            'end_date'          => ['required', 'date', 'after_or_equal:start_date'],
            'status'            => ['required', 'string', $statusIn],
            'is_active'         => ['nullable', 'boolean'],
            'description'       => ['nullable', 'string', 'max:1000'],
        ];

        return $common;
    }

    /**
     * رسائل الأخطاء المخصصة.
     */
    public function messages(): array
    {
        return [
            'name.required'         => 'اسم الفصل مطلوب.',
            'name.min'              => 'اسم الفصل قصير جداً.',
            'name.max'              => 'اسم الفصل طويل أكثر من اللازم.',
            'name.unique'           => 'اسم الفصل مستخدم مسبقاً.',

            'code.required'         => 'الرمز التعريفي مطلوب.',
            'code.min'              => 'الرمز قصير جداً.',
            'code.max'              => 'الرمز طويل أكثر من اللازم.',
            'code.unique'           => 'الرمز التعريفي مستخدم مسبقاً.',

            'academic_year_id.integer' => 'معرّف السنة يجب أن يكون رقماً.',
            'academic_year_id.exists'  => 'السنة الأكاديمية غير موجودة.',

            'start_date.required'   => 'تاريخ البداية مطلوب.',
            'start_date.date'       => 'تاريخ البداية غير صالح.',

            'end_date.required'     => 'تاريخ النهاية مطلوب.',
            'end_date.date'         => 'تاريخ النهاية غير صالح.',
            'end_date.after_or_equal' => 'تاريخ النهاية يجب أن يكون بعد أو يساوي تاريخ البداية.',

            'status.required'       => 'حالة الفصل مطلوبة.',
            'status.in'             => 'قيمة الحالة غير مدعومة. القيم المسموحة: planned, active, completed, archived.',

            'is_active.boolean'     => 'حقل التفعيل يجب أن يكون قيمة منطقية.',
            'description.max'       => 'الوصف تجاوز الحد الأقصى للطول.',
        ];
    }

    /**
     * أسماء الحقول الودية لرسائل الأخطاء.
     */
    public function attributes(): array
    {
        return [
            'name'             => 'اسم الفصل',
            'code'             => 'الرمز التعريفي',
            'academic_year_id' => 'السنة الأكاديمية',
            'start_date'       => 'تاريخ البداية',
            'end_date'         => 'تاريخ النهاية',
            'status'           => 'الحالة',
            'is_active'        => 'مفعل',
            'description'      => 'الوصف',
        ];
    }
}

/**
 * دالة مساعده للتحقق من الأعمدة بأ*
