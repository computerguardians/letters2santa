<?php

namespace App\Services;

use App\Models\Letter;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;

/**
 * Works out when a letter's Santa email should go out:
 * 25 Dec 07:00 in the account's timezone, or shortly after payment when paid after the cutoff.
 */
class SantaScheduler
{
    /** The Santa moment in a given timezone, as UTC. */
    public function santaMoment(string $timezone): CarbonImmutable
    {
        return CarbonImmutable::parse(config('letters.santa_date').' '.config('letters.santa_send_time'), $timezone)->utc();
    }

    public function cutoff(): CarbonImmutable
    {
        return CarbonImmutable::parse(config('letters.santa_cutoff_at'), config('letters.santa_cutoff_tz'))->utc();
    }

    public function isAfterCutoff(?CarbonInterface $at = null): bool
    {
        $at = CarbonImmutable::instance($at ?? now())->utc();

        return $at->greaterThanOrEqualTo($this->cutoff());
    }

    /**
     * @return array{scheduled_for: CarbonImmutable, is_late: bool}
     */
    public function resolve(Letter $letter, ?CarbonInterface $paidAt = null): array
    {
        $paidAt = CarbonImmutable::instance($paidAt ?? now())->utc();
        $timezone = $letter->timezone ?: config('letters.default_timezone');
        $santa = $this->santaMoment($timezone);

        $isLate = $this->isAfterCutoff($paidAt) || $paidAt->greaterThanOrEqualTo($santa);

        return [
            'scheduled_for' => $isLate
                ? $paidAt->addMinutes((int) config('letters.late_send_delay_minutes'))
                : $santa,
            'is_late' => $isLate,
        ];
    }

    public function apply(Letter $letter, ?CarbonInterface $paidAt = null): Letter
    {
        $resolved = $this->resolve($letter, $paidAt);
        $letter->santa_scheduled_for = $resolved['scheduled_for'];
        $letter->is_late = $resolved['is_late'];

        return $letter;
    }
}
