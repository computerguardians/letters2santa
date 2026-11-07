<?php

namespace App\Jobs;

use App\Mail\ElfArrivalMail;
use App\Models\Letter;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendElfEmail implements ShouldQueue
{
    use Queueable;

    public int $tries = 5;

    public function backoff(): array
    {
        return [60, 300, 900, 3600];
    }

    public function __construct(public Letter $letter) {}

    public function handle(): void
    {
        $letter = $this->letter->fresh(['images']);
        if (! $letter || $letter->elf_sent_at || ! $letter->isSettled()) {
            return;
        }

        Mail::to($letter->parent_email)->send(new ElfArrivalMail($letter));

        $letter->forceFill(['elf_sent_at' => now()])->save();
    }
}
