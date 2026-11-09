<?php

namespace App\Http\Requests;

use App\Support\Countries;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->business !== null;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'event_date' => ['nullable', 'date', 'after_or_equal:today'],
            'town' => ['required', 'string', 'max:60'],
            'country_code' => ['required', 'string', 'size:2', Rule::in(Countries::CODES)],
            'logo' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'remove_logo' => ['nullable', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => trim((string) $this->input('name')),
            'town' => trim((string) $this->input('town')),
            'country_code' => strtoupper((string) $this->input('country_code')),
            'event_date' => $this->input('event_date') ?: null,
        ]);
    }
}
