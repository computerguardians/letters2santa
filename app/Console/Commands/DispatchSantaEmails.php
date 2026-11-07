<?php

namespace App\Console\Commands;

use App\Jobs\SendSantaEmail;
use App\Models\Letter;
use Illuminate\Console\Command;

class DispatchSantaEmails extends Command
{
    protected $signature = 'letters:dispatch-santa {--limit=5000 : Max letters to queue per run}';

    protected $description = 'Queue Santa emails for letters whose scheduled time has arrived';

    public function handle(): int
    {
        // Recover letters whose job was lost (dispatched over 2 hours ago, never sent).
        $recovered = Letter::whereNull('santa_sent_at')
            ->whereNotNull('santa_dispatched_at')
            ->where('santa_dispatched_at', '<', now()->subHours(2))
            ->update(['santa_dispatched_at' => null]);
        if ($recovered) {
            $this->warn("Re-queued {$recovered} stale letter(s).");
        }

        $queued = 0;
        Letter::awaitingSanta()
            ->orderBy('santa_scheduled_for')
            ->limit((int) $this->option('limit'))
            ->get()
            ->each(function (Letter $letter) use (&$queued) {
                // Atomic claim so two overlapping runs can't both queue the same letter.
                $claimed = Letter::whereKey($letter->id)
                    ->whereNull('santa_dispatched_at')
                    ->update(['santa_dispatched_at' => now()]);
                if ($claimed) {
                    SendSantaEmail::dispatch($letter)->onQueue('santa');
                    $queued++;
                }
            });

        $this->info("Queued {$queued} Santa email(s).");

        return self::SUCCESS;
    }
}
