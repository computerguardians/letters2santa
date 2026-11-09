<?php

namespace App\Http\Requests;

use App\Rules\Turnstile;

/** Guest letter at /party/{slug}: the parent letter form plus parent email and a browser-detected timezone. */
class StorePartyLetterRequest extends StoreLetterRequest
{
    public function rules(): array
    {
        return parent::rules() + [
            'parent_email' => ['required', 'string', 'email:rfc', 'max:255'],
            'parent_name' => ['nullable', 'string', 'max:100'],
            'timezone' => ['nullable', 'string', 'timezone:all'],
            'cf-turnstile-response' => ['nullable', 'string', new Turnstile],
        ];
    }

    public function messages(): array
    {
        return parent::messages() + [
            'parent_email.required' => "We need a parent or guardian's email so Santa knows where to reply.",
            'parent_email.email' => 'Please check the email address. Santa\'s reply goes there on Christmas morning.',
        ];
    }

    protected function prepareForValidation(): void
    {
        parent::prepareForValidation();

        $this->merge([
            'parent_email' => strtolower(trim((string) $this->input('parent_email'))),
            'timezone' => $this->input('timezone') ?: null,
        ]);
    }
}
