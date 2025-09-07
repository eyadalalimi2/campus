<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CountryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('admin')->check();
    }

    public function rules(): array
    {
        return [
            'name_ar'      => ['required','string','max:150'],
            'iso2'         => ['nullable','string','size:2'],
            'phone_code'   => ['nullable','string','max:10'],
            'currency_code'=> ['nullable','string','size:3'],
            'is_active'    => ['boolean'],
        ];
    }
}
