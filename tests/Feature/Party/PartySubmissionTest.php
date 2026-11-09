<?php

namespace Tests\Feature\Party;

use App\Actions\SubmitPartyLetter;
use App\Enums\LetterSource;
use App\Enums\PaymentStatus;
use App\Exceptions\NoCreditsException;
use App\Jobs\ModerateLetter;
use App\Jobs\SendElfEmail;
use App\Models\Event;
use App\Models\Letter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class PartySubmissionTest extends TestCase
{
    use RefreshDatabase;

    private function data(array $overrides = []): array
    {
        return array_merge([
            'child_first_name' => 'Liam',
            'child_age' => 6,
            'town' => 'Werribee',
            'country_code' => 'AU',
            'body' => 'Dear Santa, I would like a red bike.',
            'parent_email' => 'parent@example.com',
            'parent_name' => 'Jo',
            'timezone' => 'Australia/Melbourne',
            'guardian_confirmed' => '1',
        ], $overrides);
    }

    public function test_party_page_loads_for_an_active_event(): void
    {
        $event = Event::factory()->withCredits(10)->create(['name' => 'Big Bash']);
        $this->get("/party/{$event->slug}")->assertOk()->assertSee('Big Bash')->assertSee('Send to the North Pole');
    }

    public function test_unknown_slug_is_404(): void
    {
        $this->get('/party/nope123456')->assertNotFound();
    }

    public function test_guest_letter_deducts_a_credit_and_is_credited_immediately(): void
    {
        Queue::fake();
        $event = Event::factory()->withCredits(10)->create();

        $this->post("/party/{$event->slug}", $this->data(['wall_consent' => '1']))
            ->assertRedirect();

        $this->assertEquals(9, $event->fresh()->credits_remaining);

        $letter = Letter::first();
        $this->assertEquals($event->id, $letter->event_id);
        $this->assertNull($letter->user_id);
        $this->assertEquals(LetterSource::Party, $letter->source);
        $this->assertEquals(PaymentStatus::Credited, $letter->payment_status);
        $this->assertEquals('parent@example.com', $letter->parent_email);
        $this->assertEquals('Australia/Melbourne', $letter->timezone);
        $this->assertTrue($letter->wall_consent);
        $this->assertTrue($letter->guardian_confirmed);
        $this->assertNotNull($letter->paid_at);
        // 25 Dec 07:00 Melbourne = 24 Dec 20:00 UTC
        $this->assertEquals('2026-12-24 20:00:00', $letter->santa_scheduled_for->utc()->format('Y-m-d H:i:s'));

        Queue::assertPushed(SendElfEmail::class, 1);
        Queue::assertPushed(ModerateLetter::class, 1);
    }

    public function test_parent_email_and_guardian_confirmation_are_required(): void
    {
        $event = Event::factory()->withCredits(10)->create();
        $this->post("/party/{$event->slug}", $this->data(['parent_email' => '']))->assertSessionHasErrors('parent_email');
        $this->post("/party/{$event->slug}", $this->data(['parent_email' => 'not-an-email']))->assertSessionHasErrors('parent_email');
        $this->post("/party/{$event->slug}", $this->data(['guardian_confirmed' => null]))->assertSessionHasErrors('guardian_confirmed');
        $this->post("/party/{$event->slug}", $this->data(['body' => '']))->assertSessionHasErrors('body');
        $this->assertEquals(0, Letter::count());
        $this->assertEquals(10, $event->fresh()->credits_remaining);
    }

    public function test_credits_cannot_go_below_zero(): void
    {
        Queue::fake();
        $event = Event::factory()->withCredits(1)->create();

        $this->post("/party/{$event->slug}", $this->data())->assertRedirect("/party/{$event->slug}/sent?ref=".Letter::first()->uuid);
        $this->post("/party/{$event->slug}", $this->data(['child_first_name' => 'Ava']))
            ->assertRedirect("/party/{$event->slug}")->assertSessionHas('error');

        $this->assertEquals(0, $event->fresh()->credits_remaining);
        $this->assertEquals(1, Letter::count());

        $this->get("/party/{$event->slug}")->assertOk()->assertSee('letters have all been sent')->assertDontSee('Send to the North Pole');
    }

    public function test_deduction_is_atomic_when_the_locked_row_has_no_credits(): void
    {
        // Simulates the losing side of a race: the row we lock has already been drained.
        Queue::fake();
        $event = Event::factory()->withCredits(1)->create();
        Event::whereKey($event->id)->update(['credits_remaining' => 0]);

        $this->expectException(NoCreditsException::class);
        app(SubmitPartyLetter::class)->handle($event, $this->data(), []);
    }

    public function test_paused_and_closed_events_do_not_accept_letters(): void
    {
        foreach ([Event::factory()->paused(), Event::factory()->closed()] as $factory) {
            $event = $factory->withCredits(10)->create();
            $this->post("/party/{$event->slug}", $this->data())->assertRedirect("/party/{$event->slug}");
            $this->assertEquals(10, $event->fresh()->credits_remaining);
        }
        $this->assertEquals(0, Letter::count());
    }

    public function test_screen_counts_all_letters_but_only_names_consented_approved_ones(): void
    {
        $event = Event::factory()->withCredits(10)->create();
        Letter::factory()->credited()->approved()->wallConsent()->for($event)->create(['child_first_name' => 'Shown', 'user_id' => null]);
        Letter::factory()->credited()->approved()->for($event)->create(['child_first_name' => 'Hidden', 'user_id' => null]);
        Letter::factory()->credited()->wallConsent()->for($event)->create(['child_first_name' => 'Pending', 'user_id' => null]);
        Letter::factory()->credited()->rejected()->wallConsent()->for($event)->create(['child_first_name' => 'Rejected', 'user_id' => null]);

        $json = $this->getJson("/party/{$event->slug}/screen.json")->assertOk()->json();
        $this->assertEquals(4, $json['count']);
        $this->assertEquals(['Shown'], array_column($json['recent'], 'name'));

        $this->get("/party/{$event->slug}/screen")->assertOk()->assertSee('Letters to Santa');
    }

    public function test_host_sees_letters_without_parent_emails_and_csv_has_no_emails(): void
    {
        $event = Event::factory()->withCredits(10)->create();
        Letter::factory()->credited()->approved()->for($event)->create(['child_first_name' => 'Zara', 'town' => 'Hoppers Crossing', 'parent_email' => 'secret@example.com', 'user_id' => null]);

        $page = $this->actingAs($event->business->user)->get("/host/events/{$event->id}")->assertOk();
        $page->assertSee('Zara')->assertSee('Hoppers Crossing')->assertDontSee('secret@example.com');

        $csv = $this->actingAs($event->business->user)->get("/host/events/{$event->id}/letters.csv")->assertOk();
        $body = $csv->streamedContent();
        $this->assertStringContainsString('Zara', $body);
        $this->assertStringContainsString('Hoppers Crossing', $body);
        $this->assertStringNotContainsString('secret@example.com', $body);
    }

    public function test_party_submissions_are_rate_limited_per_ip(): void
    {
        Queue::fake();
        $event = Event::factory()->withCredits(100)->create();
        $limit = (int) config('letters.party.rate_limit_per_ip_per_minute');

        for ($i = 0; $i < $limit; $i++) {
            $this->post("/party/{$event->slug}", $this->data(['child_first_name' => 'Kid '.chr(65 + $i)]))->assertRedirect();
        }
        $this->post("/party/{$event->slug}", $this->data(['child_first_name' => 'One too many']))->assertStatus(429);
        $this->assertEquals($limit, Letter::count());
    }
}
