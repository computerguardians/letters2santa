<?php

namespace Tests\Feature\Party;

use App\Enums\EnquiryStatus;
use App\Enums\EnquiryType;
use App\Mail\PartyEnquiryNotificationMail;
use App\Mail\PartyEnquiryReceivedMail;
use App\Models\PartyEnquiry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PartyEnquiryTest extends TestCase
{
    use RefreshDatabase;

    private function payload(array $overrides = []): array
    {
        return $overrides + [
            'type' => 'demo',
            'organisation' => 'Tarneit Primary School',
            'contact_name' => 'Jo Bloggs',
            'email' => 'Jo@Example.com',
            'phone' => '0400 000 000',
            'country_code' => 'AU',
            'event_date' => now()->addWeeks(2)->toDateString(),
            'expected_guests' => 120,
            'message' => 'Carols night in the hall.',
            'website' => '',
        ];
    }

    public function test_landing_page_links_to_demo_and_setup_help(): void
    {
        $this->get('/party-packs')->assertOk()
            ->assertSee('Book a demo')
            ->assertSee(route('party.enquire', ['type' => 'demo']), false)
            ->assertSee(route('party.enquire', ['type' => 'setup']), false);
    }

    public function test_enquiry_form_preselects_type_from_query(): void
    {
        $this->get('/party-packs/enquire?type=setup')->assertOk()->assertSee('Help me set it up');
        $this->get('/party-packs/enquire?type=bogus')->assertOk();
    }

    public function test_submitting_an_enquiry_stores_it_and_emails_support_and_the_enquirer(): void
    {
        Mail::fake();
        config(['letters.business.support_email' => 'workshop@example.test']);

        $this->post('/party-packs/enquire', $this->payload())
            ->assertRedirect(route('party.enquire.thanks'));

        $enquiry = PartyEnquiry::sole();
        $this->assertSame(EnquiryType::Demo, $enquiry->type);
        $this->assertSame(EnquiryStatus::New, $enquiry->status);
        $this->assertSame('jo@example.com', $enquiry->email);
        $this->assertSame(120, $enquiry->expected_guests);
        $this->assertNotNull($enquiry->ip_hash);

        Mail::assertQueued(PartyEnquiryNotificationMail::class, fn ($m) => $m->hasTo('workshop@example.test') && $m->hasReplyTo('jo@example.com'));
        Mail::assertQueued(PartyEnquiryReceivedMail::class, fn ($m) => $m->hasTo('jo@example.com'));

        $this->get(route('party.enquire.thanks'))->assertOk()->assertSee('short demo');
    }

    public function test_thanks_page_redirects_without_a_submission(): void
    {
        $this->get('/party-packs/enquire/thanks')->assertRedirect(route('party.enquire'));
    }

    public function test_validation_rejects_bad_input_and_honeypot(): void
    {
        Mail::fake();

        $this->from('/party-packs/enquire')->post('/party-packs/enquire', $this->payload(['type' => 'sales', 'email' => 'nope', 'organisation' => '']))
            ->assertRedirect('/party-packs/enquire')
            ->assertSessionHasErrors(['type', 'email', 'organisation']);

        $this->from('/party-packs/enquire')->post('/party-packs/enquire', $this->payload(['website' => 'http://spam.example']))
            ->assertSessionHasErrors(['website']);

        $this->from('/party-packs/enquire')->post('/party-packs/enquire', $this->payload(['event_date' => now()->subDay()->toDateString()]))
            ->assertSessionHasErrors(['event_date']);

        $this->assertSame(0, PartyEnquiry::count());
        Mail::assertNothingQueued();
    }

    public function test_optional_fields_can_be_blank(): void
    {
        Mail::fake();

        $this->post('/party-packs/enquire', $this->payload(['phone' => '', 'event_date' => '', 'expected_guests' => '', 'message' => '', 'type' => 'question']))
            ->assertRedirect(route('party.enquire.thanks'));

        $enquiry = PartyEnquiry::sole();
        $this->assertNull($enquiry->event_date);
        $this->assertNull($enquiry->expected_guests);
        $this->assertSame(EnquiryType::Question, $enquiry->type);
    }

    public function test_enquiry_emails_render(): void
    {
        $enquiry = PartyEnquiry::factory()->create(['type' => EnquiryType::Setup]);

        $this->assertStringContainsString('set up a party pack', (new PartyEnquiryReceivedMail($enquiry))->render());
        $this->assertStringContainsString($enquiry->organisation, (new PartyEnquiryNotificationMail($enquiry))->render());
    }

    public function test_admin_can_browse_and_update_enquiries(): void
    {
        $admin = User::factory()->admin()->create();
        $enquiry = PartyEnquiry::factory()->create(['organisation' => 'Werribee Rotary']);

        $this->actingAs($admin)->get('/admin/party-enquiries')->assertOk()->assertSee('Werribee Rotary');
        $this->actingAs($admin)->get("/admin/party-enquiries/{$enquiry->id}")->assertOk()->assertSee($enquiry->email);
    }

    public function test_enquiry_form_is_rate_limited(): void
    {
        Mail::fake();

        for ($i = 0; $i < 5; $i++) {
            $this->post('/party-packs/enquire', $this->payload())->assertRedirect();
        }
        $this->post('/party-packs/enquire', $this->payload())->assertStatus(429);
    }
}
