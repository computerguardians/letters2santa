<?php

use Illuminate\Support\Facades\Schedule;

// Santa emails: queue anything whose scheduled time has arrived. Runs all year; only acts around 25 Dec and for late letters.
Schedule::command('letters:dispatch-santa')->everyFiveMinutes()->withoutOverlapping()->runInBackground();

// Data retention (Phase 3): purge letter content N days after Santa day.
Schedule::command('letters:retention')->dailyAt('03:30')->withoutOverlapping();

Schedule::command('queue:prune-failed --hours=720')->daily();
