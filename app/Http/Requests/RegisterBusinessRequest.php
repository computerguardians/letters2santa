<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Support\Countries;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterBusinessRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'business_name' => ['required', 'string', 'max:120'],
            'abn' => ['nullable', 'string', 'max:20', 'regex:/^[0-9 ]{11,14}$/'],
            'contact_name' => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:30'],
            'country_code' => ['required', 'string', 'size:2', Rule::in(Countries::CODES)],
            'logo' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'terms' => ['accepted'],
        ];

        // Logged-in users (e.g. a parent who also hosts a party) only add the business profile.
        if (! $this->user()) {
            $rules += [
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
                'password' => ['required', 'confirmed', Password::defaults()],
                'timezone' => ['nullable', 'string', 'timezone:all'],
            ];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'abn.regex' => 'An ABN is 11 digits.',
            'terms.accepted' => 'Please accept the terms and privacy policy.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'business_name' => trim((string) $this->input('business_name')),
            'contact_name' => trim((string) $this->input('contact_name')),
            'abn' => $this->input('abn') ? preg_replace('/\s+/', '', (string) $this->input('abn')) : null,
            'country_code' => strtoupper((string) $this->input('country_code', 'AU')),
            'email' => strtolower(trim((string) $this->input('email'))),
        ]);
    }
}
