<?php

namespace App\Actions;

use App\Enums\LetterSource;
use App\Enums\PaymentStatus;
use App\Exceptions\NoCreditsException;
use App\Models\Event;
use App\Models\Letter;
use App\Services\ImageService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

/**
 * Creates a guest letter on a party host's credits. The credit is deducted inside a transaction with
 * the event row locked, so concurrent submissions can never take credits below zero.
 */
class SubmitPartyLetter
{
    public function __construct(private ImageService $images, private SettleLetters $settle) {}

    /**
     * @param  array<string, mixed>  $data  validated form data
     * @param  array<int, UploadedFile>  $files
     *
     * @throws NoCreditsException when the event is not active or has no credits left
     */
    public function handle(Event $event, array $data, array $files, ?string $ipHash = null): Letter
    {
        $letter = DB::transaction(function () use ($event, $data, $files, $ipHash) {
            $locked = Event::whereKey($event->id)->lockForUpdate()->first();

            if (! $locked->isAcceptingLetters()) {
                throw new NoCreditsException($locked);
            }

            $locked->decrement('credits_remaining');

            $letter = Letter::create([
                'user_id' => null,
                'event_id' => $locked->id,
                'parent_email' => strtolower(trim($data['parent_email'])),
                'parent_name' => filled($data['parent_name'] ?? null) ? trim($data['parent_name']) : null,
                'child_first_name' => $data['child_first_name'],
                'child_age' => $data['child_age'] ?? null,
                'town' => $data['town'],
                'country_code' => $data['country_code'],
                'body' => filled($data['body'] ?? null) ? trim($data['body']) : null,
                'timezone' => $data['timezone'] ?? config('letters.default_timezone'),
                'source' => LetterSource::Party,
                'payment_status' => PaymentStatus::Pending,
                'wall_consent' => (bool) ($data['wall_consent'] ?? false),
                'instagram_consent' => (bool) ($data['instagram_consent'] ?? false),
                'guardian_confirmed' => true,
                'consent_at' => now(),
                'ip_hash' => $ipHash,
            ]);

            foreach ($files as $file) {
                $this->images->store($file, $letter);
            }

            return $letter;
        });

        // Marks the letter credited, schedules Santa, queues the Elf email and moderation.
        $this->settle->handle(collect([$letter]), PaymentStatus::Credited);

        return $letter->fresh();
    }
}
