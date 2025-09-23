<?php
namespace App\Http\Requests\Medical;
use Illuminate\Foundation\Http\FormRequest;

class SystemRequest extends FormRequest {
    public function authorize(): bool { return true; }
    public function rules(): array {
         $id = $this->route('system')?->id;
        return [
            'code' => 'required|string|max:50|unique:med_systems,code,'.($id??'null'),
            'name_ar' => 'required|string|max:191',
            'name_en' => 'nullable|string|max:191',
            'icon_url' => 'nullable|url|max:255',
            'display_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean'
        ];
    }
}
