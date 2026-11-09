<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Cloudflare Turnstile check. Passes automatically when no secret key is configured
 * (local/test), so the party form keeps working without keys.
 */
class Turnstile implements ValidationRule
{
    public static function enabled(): bool
    {
        return filled(config('letters.turnstile.secret_key')) && filled(config('letters.turnstile.site_key'));
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! self::enabled()) {
            return;
        }

        if (blank($value)) {
            $fail('Please complete the "I am human" check.');

            return;
        }

        try {
            $response = Http::timeout(5)->asForm()->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                'secret' => config('letters.turnstile.secret_key'),
                'response' => $value,
                'remoteip' => request()->ip(),
            ]);
            if (! $response->json('success')) {
                $fail('The "I am human" check failed. Please try again.');
            }
        } catch (\Throwable $e) {
            Log::warning('Turnstile verify failed: '.$e->getMessage());
            // Fail open on a Cloudflare outage; the per-IP and per-event rate limits still apply.
        }
    }
}
