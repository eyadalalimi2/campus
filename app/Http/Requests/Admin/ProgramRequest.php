<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProgramRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('admin')->check();
    }

    public function rules(): array
    {
        // في التحديث سيصلنا الموديل عبر binding: /admin/programs/{program}
        $program    = $this->route('program');
        $programId  = is_object($program) ? $program->id : ($program ?: null);
        $discipline = $this->input('discipline_id');

        return [
            'discipline_id' => ['required', 'exists:disciplines,id'],
            'name' => [
                'required', 'string', 'max:150',
                // فريد داخل نفس المجال (discipline_id)
                Rule::unique('programs', 'name')
                    ->where(fn ($q) => $q->where('discipline_id', $discipline))
                    ->ignore($programId),
            ],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function validated($key = null, $default = null)
    {
        $data = parent::validated();
        $data['is_active'] = $this->boolean('is_active');
        return $data;
    }
}
