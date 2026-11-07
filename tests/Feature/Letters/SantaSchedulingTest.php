<?php

namespace Tests\Feature\Letters;

use App\Jobs\SendSantaEmail;
use App\Models\Letter;
use App\Services\SantaScheduler;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class SantaSchedulingTest extends TestCase
{
    use RefreshDatabase;

    /** @dataProvider timezones */
    public function test_santa_email_is_scheduled_for_7am_local_on_christmas(string $tz, string $expectedUtc): void
    {
        $letter = Letter::factory()->make(['timezone' => $tz]);
        $paidAt = CarbonImmutable::parse('2026-12-01 10:00:00', 'UTC');

        $result = app(SantaScheduler::class)->resolve($letter, $paidAt);

        $this->assertFalse($result['is_late']);
        $this->assertEquals($expectedUtc, $result['scheduled_for']->utc()->format('Y-m-d H:i'));
    }

    public static function timezones(): array
    {
        return [
            'Sydney (AEDT +11)' => ['Australia/Sydney', '2026-12-24 20:00'],
            'Perth (+8)' => ['Australia/Perth', '2026-12-24 23:00'],
            'Auckland (+13)' => ['Pacific/Auckland', '2026-12-24 18:00'],
            'London (+0)' => ['Europe/London', '2026-12-25 07:00'],
            'New York (-5)' => ['America/New_York', '2026-12-25 12:00'],
            'Los Angeles (-8)' => ['America/Los_Angeles', '2026-12-25 15:00'],
            'Honolulu (-10)' => ['Pacific/Honolulu', '2026-12-25 17:00'],
        ];
    }

    public function test_letters_paid_after_cutoff_are_sent_within_the_hour(): void
    {
        $letter = Letter::factory()->make(['timezone' => 'Australia/Sydney']);
        // Cutoff is 24 Dec 18:00 AEDT = 24 Dec 07:00 UTC
        $paidAt = CarbonImmutable::parse('2026-12-24 07:30:00', 'UTC');

        $result = app(SantaScheduler::class)->resolve($letter, $paidAt);

        $this->assertTrue($result['is_late']);
        $this->assertTrue($result['scheduled_for']->between($paidAt, $paidAt->addHour()));
    }

    public function test_letter_paid_one_minute_before_cutoff_waits_for_christmas(): void
    {
        $letter = Letter::factory()->make(['timezone' => 'Australia/Sydney']);
        $paidAt = CarbonImmutable::parse('2026-12-24 06:59:00', 'UTC');

        $result = app(SantaScheduler::class)->resolve($letter, $paidAt);

        $this->assertFalse($result['is_late']);
        $this->assertEquals('2026-12-24 20:00', $result['scheduled_for']->utc()->format('Y-m-d H:i'));
    }

    public function test_letter_paid_after_christmas_is_sent_promptly(): void
    {
        $letter = Letter::factory()->make(['timezone' => 'America/Los_Angeles']);
        $paidAt = CarbonImmutable::parse('2026-12-27 10:00:00', 'UTC');

        $result = app(SantaScheduler::class)->resolve($letter, $paidAt);

        $this->assertTrue($result['is_late']);
        $this->assertTrue($result['scheduled_for']->lessThanOrEqualTo($paidAt->addHour()));
    }

    public function test_is_after_cutoff_uses_configured_timezone(): void
    {
        $scheduler = app(SantaScheduler::class);
        $this->assertFalse($scheduler->isAfterCutoff(CarbonImmutable::parse('2026-12-24 17:59', 'Australia/Sydney')));
        $this->assertTrue($scheduler->isAfterCutoff(CarbonImmutable::parse('2026-12-24 18:00', 'Australia/Sydney')));
    }

    public function test_dispatcher_queues_only_due_letters_once(): void
    {
        Queue::fake();
        Carbon::setTestNow('2026-12-24 20:05:00'); // UTC, just after Sydney 07:00

        $due = Letter::factory()->paid()->create(['santa_scheduled_for' => '2026-12-24 20:00:00']);
        $notDue = Letter::factory()->paid()->create(['santa_scheduled_for' => '2026-12-24 23:00:00']); // Perth
        $unpaid = Letter::factory()->create(['santa_scheduled_for' => '2026-12-24 20:00:00']);
        $alreadySent = Letter::factory()->paid()->create(['santa_scheduled_for' => '2026-12-24 20:00:00', 'santa_sent_at' => now()]);

        $this->artisan('letters:dispatch-santa')->assertSuccessful();
        $this->artisan('letters:dispatch-santa')->assertSuccessful();

        Queue::assertPushed(SendSantaEmail::class, 1);
        Queue::assertPushed(SendSantaEmail::class, fn ($job) => $job->letter->id === $due->id);
        $this->assertNotNull($due->fresh()->santa_dispatched_at);
        $this->assertNull($notDue->fresh()->santa_dispatched_at);
        $this->assertNull($unpaid->fresh()->santa_dispatched_at);

        Carbon::setTestNow();
    }
}
