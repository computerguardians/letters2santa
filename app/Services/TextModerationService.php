<?php

namespace App\Services;

/**
 * Cheap, conservative text checks. Any flag sends the letter to the manual queue; nothing is auto-rejected.
 */
class TextModerationService
{
    private const STREET_TYPES = 'street|st|road|rd|avenue|ave|av|drive|dr|court|ct|place|pl|crescent|cres|lane|ln|way|parade|pde|highway|hwy|boulevard|blvd|close|cl|terrace|tce|circuit|cct|grove|gr|esplanade|esp|square|sq|rise|walk|track|trk';

    /**
     * @return string[] flags, e.g. ['profanity', 'phone']
     */
    public function check(?string $body, ?string $childName = null): array
    {
        $flags = [];
        $text = mb_strtolower(trim((string) $body));

        if ($text !== '') {
            if ($this->containsProfanity($text)) {
                $flags[] = 'profanity';
            }
            if (preg_match('/[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}/i', $text)) {
                $flags[] = 'email';
            }
            if ($this->containsPhoneNumber($text)) {
                $flags[] = 'phone';
            }
            if (preg_match('~(https?://|www\.)\S+~i', $text)) {
                $flags[] = 'url';
            }
            if (preg_match('/\b\d{1,5}[a-z]?\s+(?:[a-z\'\-]+\s){0,3}(?:'.self::STREET_TYPES.')\b\.?/i', $text)) {
                $flags[] = 'address';
            }
            foreach (config('profanity.pii_hints', []) as $hint) {
                if (preg_match('/\b'.preg_quote($hint, '/').'\b/u', $text)) {
                    $flags[] = 'pii_hint';
                    break;
                }
            }
        }

        if ($childName !== null) {
            $name = trim($childName);
            if (preg_match('/\s/u', $name)) {
                $flags[] = 'possible_surname';
            }
            if ($this->containsProfanity(mb_strtolower($name))) {
                $flags[] = 'profanity';
            }
        }

        return array_values(array_unique($flags));
    }

    public function containsProfanity(string $lowerText): bool
    {
        // Normalise common substitutions (f*ck, sh1t).
        $normalised = strtr($lowerText, ['*' => 'u', '1' => 'i', '3' => 'e', '0' => 'o', '@' => 'a', '$' => 's', '!' => 'i']);
        foreach (config('profanity.words', []) as $word) {
            $pattern = '/\b'.preg_quote($word, '/').'\b/u';
            if (preg_match($pattern, $lowerText) || preg_match($pattern, $normalised)) {
                return true;
            }
        }

        return false;
    }

    public function containsPhoneNumber(string $text): bool
    {
        if (! preg_match_all('/\+?\d[\d\s\-().]{6,}\d/', $text, $m)) {
            return false;
        }
        foreach ($m[0] as $candidate) {
            $digits = preg_replace('/\D/', '', $candidate);
            if (strlen($digits) >= 8 && strlen($digits) <= 15) {
                return true;
            }
        }

        return false;
    }
}
