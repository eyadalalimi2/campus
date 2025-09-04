<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubscriptionRequest extends FormRequest
{
    public function authorize(): bool { return auth('admin')->check(); }

    public function rules(): array
    {
        return [
            'user_id'   => ['required','exists:users,id'],
            'plan'      => ['required','string','max:100'],
            'status'    => ['required', Rule::in(['active','expired','canceled'])],

            'started_at'=> ['nullable','date'],
            'ends_at'   => ['nullable','date','after_or_equal:started_at'],

            'auto_renew'=> ['nullable','boolean'],
            'price_cents'=>['nullable','integer','min:0'],
            'currency'  => ['required','string','size:3'],
        ];
    }
}
