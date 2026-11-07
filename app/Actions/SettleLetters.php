<?php

namespace App\Actions;

use App\Enums\PaymentStatus;
use App\Jobs\ModerateLetter;
use App\Jobs\SendElfEmail;
use App\Models\Letter;
use App\Services\SantaScheduler;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Runs once a letter is paid (Stripe) or credited (party). Idempotent: already-settled letters are skipped.
 * Sets the Santa schedule, then queues the Elf email and moderation.
 */
class SettleLetters
{
    public function __construct(private SantaScheduler $scheduler) {}

    /**
     * @param  Collection<int, Letter>  $letters
     * @return Collection<int, Letter> the letters that were newly settled
     */
    public function handle(Collection $letters, PaymentStatus $status, ?CarbonInterface $paidAt = null): Collection
    {
        $paidAt ??= now();

        $settled = DB::transaction(function () use ($letters, $status, $paidAt) {
            $done = collect();
            foreach ($letters as $letter) {
                $fresh = Letter::whereKey($letter->id)->lockForUpdate()->first();
                if (! $fresh || $fresh->payment_status->isSettled()) {
                    continue;
                }
                $fresh->payment_status = $status;
                $fresh->paid_at = $paidAt;
                $this->scheduler->apply($fresh, $paidAt);
                $fresh->save();
                $done->push($fresh);
            }

            return $done;
        });

        foreach ($settled as $letter) {
            SendElfEmail::dispatch($letter)->onQueue('emails');
            ModerateLetter::dispatch($letter);
        }

        return $settled;
    }
}
