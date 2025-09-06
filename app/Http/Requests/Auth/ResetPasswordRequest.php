<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

/**
 * @mixin \Illuminate\Http\Request
 */
class ResetPasswordRequest extends FormRequest
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
            'token'    => ['required','string'],
            // لا نستخدم exists هنا لنفس سبب منع التعداد
            'email'    => ['required','email','max:255'],
            'password' => ['required','confirmed', Password::min(8)->letters()->mixedCase()->numbers()],
        ];
    }
}
