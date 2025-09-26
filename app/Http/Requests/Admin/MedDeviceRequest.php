<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class MedDeviceRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $device = $this->route('device');
        $id = $device?->id;

        return [
            'name' => ['required','string','max:255'],
            'image' => ['nullable','image','mimes:png,jpg,jpeg,webp','max:2048'],
            'order_index' => ['nullable','integer','min:0'],
            'status' => ['required','in:draft,published'],
            'slug' => ['required','string','max:255','unique:med_devices,slug,'.($id??'null')],
            'subject_ids' => ['array'],
            'subject_ids.*' => ['integer','exists:med_subjects,id'],
        ];
    }
}
