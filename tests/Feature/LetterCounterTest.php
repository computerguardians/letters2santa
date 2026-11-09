<?php

namespace Tests\Feature;

use App\Enums\PaymentStatus;
use App\Models\Letter;
use App\Services\LetterCounter;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class LetterCounterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
        config()->set('letters.wall.seed_counter', [
            'enabled' => true, 'target' => 555, 'floor' => 80, 'start' => '2026-11-01', 'end' => '2026-12-20',
        ]);
    }

    protected function tearDown(): void
    {
        CarbonImmutable::setTestNow();
        parent::tearDown();
    }

    public function test_shows_the_floor_before_the_season_starts(): void
    {
        CarbonImmutable::setTestNow('2026-10-15 12:00:00');
        $this->assertSame(80, app(LetterCounter::class)->display());
    }

    public function test_climbs_to_the_target_by_the_end_date_and_never_beyond(): void
    {
        CarbonImmutable::setTestNow('2026-12-20 23:59:59');
        $this->assertSame(555, app(LetterCounter::class)->display());

        CarbonImmutable::setTestNow('2027-01-05 00:00:00');
        $this->assertSame(555, app(LetterCounter::class)->display());
    }

    public function test_is_replaced_by_the_real_count_once_real_letters_overtake_it(): void
    {
        CarbonImmutable::setTestNow('2026-10-15 12:00:00');
        Letter::factory()->count(3)->create(['payment_status' => PaymentStatus::Paid]);
        $counter = app(LetterCounter::class);
        $this->assertSame(80, $counter->display());
        $this->assertFalse($counter->isReal());

        Cache::flush();
        Letter::factory()->count(90)->create(['payment_status' => PaymentStatus::Paid]);
        $counter = app(LetterCounter::class);
        $this->assertSame(93, $counter->display());
        $this->assertTrue($counter->isReal());
    }

    public function test_shows_only_the_real_count_when_the_seed_is_disabled(): void
    {
        config()->set('letters.wall.seed_counter.enabled', false);
        Letter::factory()->count(2)->create(['payment_status' => PaymentStatus::Paid]);
        $this->assertSame(2, app(LetterCounter::class)->display());
    }
}
