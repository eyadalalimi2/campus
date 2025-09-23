<?php
namespace App\Http\Requests\Medical;
use Illuminate\Foundation\Http\FormRequest;

class DoctorRequest extends FormRequest {
    public function authorize(): bool { return true; }
    public function rules(): array {
        return [
            'name'=>'required|string|max:191',
            'channel_url'=>'required|url|max:255',
            'country'=>'nullable|string|size:2',
            'verified'=>'boolean',
            'score'=>'nullable|numeric|min:0|max:99.99'
        ];
    }
}
