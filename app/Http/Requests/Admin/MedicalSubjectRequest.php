<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\MedicalTerm;
use App\Models\MedicalYear;

class MedicalSubjectRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'term_id'        => ['required','exists:MedicalTerms,id'],
            'med_subject_id' => ['required','exists:med_subjects,id'],
            'track'          => ['required','in:REQUIRED,SYSTEM,CLINICAL'],
            'display_name'   => ['nullable','string','max:255'],
            'notes'          => ['nullable','string'],
            'is_active'      => ['nullable','boolean'],
            'sort_order'     => ['nullable','integer','min:0'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function($v){
            $termId = (int) $this->input('term_id');
            $track  = $this->input('track');

            if (!$termId || !$track) return;

            $term = MedicalTerm::with('year')->find($termId);
            if (!$term) return;

            /** @var MedicalYear $year */
            $year = $term->year;
            if (!$year) return;

            $yn = (int) $year->year_number;

            if ($track === 'REQUIRED' && ($yn < 1 || $yn > 3)) {
                $v->errors()->add('track', 'REQUIRED مسموح فقط في السنوات 1..3');
            }
            if ($track === 'SYSTEM' && ($yn < 2 || $yn > 3)) {
                $v->errors()->add('track', 'SYSTEM مسموح فقط في السنوات 2..3');
            }
            if ($track === 'CLINICAL' && ($yn < 4 || $yn > 6)) {
                $v->errors()->add('track', 'CLINICAL مسموح فقط في السنوات 4..6');
            }
        });
    }
}