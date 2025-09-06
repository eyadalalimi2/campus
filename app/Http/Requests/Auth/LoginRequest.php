<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @mixin \Illuminate\Http\Request
 */
class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $data = $this->all();

        if (isset($data['email']) && is_string($data['email'])) {
            $data['email'] = strtolower(trim($data['email']));
        }

        $this->merge($data);
    }

    public function rules(): array
    {
        return [
            'email'    => ['required','email','max:255'],
            'password' => ['required','string','min:6'],
            'remember' => ['nullable','boolean'], // لن يؤثر على تدفق Bearer Token
        ];
    }
}
