<?php

namespace App\Http\Requests\Api\V1\StudentRequests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;
    public function authorize(): bool { return true; }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'type'    => $this->input('type') ? trim($this->input('type')) : null,
            'subject' => $this->input('subject') ? trim($this->input('subject')) : null,
            'body'    => $this->input('body') ? trim($this->input('body')) : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'type'    => ['required','string','max:50'], // مثل: enrollment, certificate, support...
            'subject' => ['required','string','max:150'],
            'body'    => ['required','string','max:4000'],
        ];
    }
}
