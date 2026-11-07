<?php

namespace App\Support;

use App\Enums\LetterSource;
use App\Enums\ModerationStatus;
use App\Enums\PaymentStatus;
use App\Models\Letter;

/** An unsaved letter used for email previews and test sends. */
class SampleLetter
{
    public static function make(array $overrides = []): Letter
    {
        $letter = new Letter(array_merge([
            'parent_email' => 'parent@example.com',
            'parent_name' => 'Alex',
            'child_first_name' => 'Emma',
            'child_age' => 7,
            'town' => 'Tarneit',
            'country_code' => 'AU',
            'body' => "Dear Santa, I have been very good this year. I helped Mum with the garden and I was kind to my brother Noah. For Christmas I would love a telescope so I can see the stars and maybe your sleigh! Please say hi to Rudolph. Love, Emma",
            'timezone' => 'Australia/Melbourne',
            'source' => LetterSource::Individual,
            'payment_status' => PaymentStatus::Paid,
            'moderation_status' => ModerationStatus::Approved,
            'paid_at' => now(),
            'santa_variant' => 1,
        ], $overrides));
        $letter->uuid = '00000000-0000-0000-0000-000000000000';
        $letter->reference = 'L2S-SAMPLE1';
        $letter->created_at = now();
        $letter->setRelation('images', collect());

        return $letter;
    }
}
