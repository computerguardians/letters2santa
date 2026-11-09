<?php

namespace App\Http\Requests;

use App\Enums\EnquiryType;
use App\Rules\Turnstile;
use App\Support\Countries;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePartyEnquiryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => ['required', Rule::enum(EnquiryType::class)],
            'organisation' => ['required', 'string', 'max:120'],
            'contact_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email:rfc', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'country_code' => ['required', 'string', 'size:2', fn ($a, $v, $fail) => Countries::isValid($v) ?: $fail('Please choose a country.')],
            'event_date' => ['nullable', 'date', 'after_or_equal:today'],
            'expected_guests' => ['nullable', 'integer', 'min:1', 'max:100000'],
            'message' => ['nullable', 'string', 'max:2000'],
            'website' => ['nullable', 'max:0'], // honeypot: must stay empty
            'cf-turnstile-response' => ['nullable', 'string', new Turnstile],
        ];
    }

    public function messages(): array
    {
        return [
            'website.max' => 'Something went wrong. Please try again.',
            'event_date.after_or_equal' => 'The event date should be today or later.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'email' => strtolower(trim((string) $this->input('email'))),
            'country_code' => strtoupper((string) $this->input('country_code', 'AU')),
            'event_date' => $this->input('event_date') ?: null,
            'expected_guests' => $this->input('expected_guests') ?: null,
        ]);
    }
}
