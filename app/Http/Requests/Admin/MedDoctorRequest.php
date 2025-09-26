<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class MedDoctorRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $doctor = $this->route('doctor');
        $id = $doctor?->id;

        return [
            'name' => ['required','string','max:255'],
            'avatar' => ['nullable','image','mimes:png,jpg,jpeg,webp','max:2048'],
            'bio' => ['nullable','string','max:2000'],
            'order_index' => ['nullable','integer','min:0'],
            'status' => ['required','in:draft,published'],
            'slug' => ['required','string','max:255','unique:med_doctors,slug,'.($id??'null')],
            'subject_ids' => ['array'],
            'subject_ids.*' => ['integer','exists:med_subjects,id'],
        ];
    }
}
