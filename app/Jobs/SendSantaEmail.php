<?php

namespace App\Jobs;

use App\Mail\SantaLetterMail;
use App\Models\Letter;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Middleware\RateLimited;
use Illuminate\Support\Facades\Mail;

class SendSantaEmail implements ShouldQueue, ShouldBeUnique
{
    use Queueable;

    public int $tries = 5;

    public int $uniqueFor = 3600;

    public function backoff(): array
    {
        return [120, 600, 1800, 3600];
    }

    public function __construct(public Letter $letter) {}

    public function uniqueId(): string
    {
        return (string) $this->letter->id;
    }

    public function middleware(): array
    {
        return [new RateLimited('santa-emails')];
    }

    public function handle(): void
    {
        $letter = $this->letter->fresh();
        if (! $letter || $letter->santa_sent_at || ! $letter->isSettled() || $letter->content_purged_at) {
            return;
        }

        if (! $letter->santa_variant) {
            $letter->santa_variant = random_int(1, max(1, (int) config('letters.santa_variants')));
            $letter->save();
        }

        Mail::to($letter->parent_email)->send(new SantaLetterMail($letter));

        $letter->forceFill(['santa_sent_at' => now()])->save();
    }

    public function failed(\Throwable $e): void
    {
        // Let the dispatcher pick it up again on a later run.
        Letter::whereKey($this->letter->id)->update(['santa_dispatched_at' => null]);
    }
}
