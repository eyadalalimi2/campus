<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AcademicCalendarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('admin')->check();
    }

    public function rules(): array
    {
        return [
            'university_id' => ['required','exists:universities,id'],
            'year_label'    => ['required','string','max:20'],
            'starts_on'     => ['required','date'],
            'ends_on'       => ['required','date','after_or_equal:starts_on'],
            'is_active'     => ['boolean'],
        ];
    }
}
