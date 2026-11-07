<?php

namespace App\Http\Requests;

use App\Services\ImageService;
use App\Support\Countries;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreLetterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $cfg = config('letters.letter');

        return [
            'child_first_name' => ['required', 'string', 'max:'.$cfg['name_max'], "regex:/^[\p{L}\p{M}'\-\. ]+$/u"],
            'child_age' => ['nullable', 'integer', 'min:'.$cfg['age_min'], 'max:'.$cfg['age_max']],
            'town' => ['required', 'string', 'max:'.$cfg['town_max']],
            'country_code' => ['required', 'string', 'size:2', Rule::in(Countries::CODES)],
            'body' => ['nullable', 'string', 'max:'.$cfg['body_max']],
            'images' => ['nullable', 'array', 'max:'.$cfg['max_images']],
            'images.*' => ['file', 'mimes:jpg,jpeg,png,heic,heif,webp', 'max:'.($cfg['max_image_mb'] * 1024)],
            'wall_consent' => ['nullable', 'boolean'],
            'instagram_consent' => ['nullable', 'boolean'],
            'guardian_confirmed' => ['accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'child_first_name.regex' => "Please use letters only for your child's first name.",
            'guardian_confirmed.accepted' => 'Please confirm you are the parent or guardian.',
            'images.*.mimes' => 'Images must be JPG, PNG, HEIC or WebP.',
            'images.*.max' => 'Each image must be under '.config('letters.letter.max_image_mb').' MB.',
            'images.max' => 'You can attach up to '.config('letters.letter.max_images').' images.',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $v) {
            $images = array_filter((array) $this->file('images', []));
            if (blank(trim((string) $this->input('body'))) && count($images) === 0) {
                $v->errors()->add('body', 'Please write a letter or add a photo of a drawing or letter (or both).');
            }
            $service = app(ImageService::class);
            foreach ($images as $i => $file) {
                if ($file && $file->isValid() && ! $service->isDecodable($file)) {
                    $v->errors()->add("images.$i", 'That image could not be read. Please try another file.');
                }
            }
        });
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'child_first_name' => trim(preg_replace('/\s+/', ' ', (string) $this->input('child_first_name'))),
            'town' => trim((string) $this->input('town')),
            'country_code' => strtoupper((string) $this->input('country_code')),
            'child_age' => $this->input('child_age') === '' ? null : $this->input('child_age'),
        ]);
    }
}
