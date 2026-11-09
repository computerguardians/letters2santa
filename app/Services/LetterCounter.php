<?php

namespace App\Services;

use App\Models\Letter;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Cache;

/**
 * The public "letters reached the North Pole this year" number.
 *
 * While the season warms up, a seeded number climbs steadily from `floor` to `target`
 * between the configured start and end dates. The moment the real settled count
 * overtakes the seeded number, the real count is shown and the seed is never used again.
 */
class LetterCounter
{
    public function real(): int
    {
        return Cache::remember('letters.count', 30, fn () => Letter::settled()->count());
    }

    public function display(): int
    {
        $real = $this->real();
        $cfg = config('letters.wall.seed_counter');

        if (! ($cfg['enabled'] ?? false)) {
            return $real;
        }

        $seed = $this->seedValue($cfg, CarbonImmutable::now());

        return max($real, $seed);
    }

    /** True when the number on display is the real count rather than the warm-up seed. */
    public function isReal(): bool
    {
        return $this->display() === $this->real();
    }

    protected function seedValue(array $cfg, CarbonImmutable $now): int
    {
        $tz = config('letters.default_timezone');
        $start = CarbonImmutable::parse($cfg['start'], $tz);
        $end = CarbonImmutable::parse($cfg['end'], $tz)->endOfDay();
        $floor = max(0, (int) $cfg['floor']);
        $target = max($floor, (int) $cfg['target']);

        if ($now->lessThan($start) || $end->lessThanOrEqualTo($start)) {
            return $floor;
        }

        $fraction = min(1, $start->diffInSeconds($now) / $start->diffInSeconds($end));

        return (int) floor($floor + ($target - $floor) * $fraction);
    }
}
