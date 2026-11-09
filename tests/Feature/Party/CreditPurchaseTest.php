<?php

namespace Tests\Feature\Party;

use App\Enums\PaymentStatus;
use App\Mail\PartyPackReadyMail;
use App\Models\Business;
use App\Models\CreditPurchase;
use App\Models\Event;
use App\Services\StripeGateway;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class CreditPurchaseTest extends TestCase
{
    use RefreshDatabase;

    private function webhook(array $event)
    {
        $payload = json_encode($event);
        $t = time();
        $sig = hash_hmac('sha256', "{$t}.{$payload}", config('services.stripe.webhook_secret'));

        return $this->call('POST', '/webhook/stripe', [], [], [], ['HTTP_STRIPE_SIGNATURE' => "t={$t},v1={$sig}", 'CONTENT_TYPE' => 'application/json'], $payload);
    }

    public function test_host_can_create_an_event(): void
    {
        $business = Business::factory()->create();

        $this->actingAs($business->user)->post('/host/events', [
            'name' => 'Office Christmas Party', 'event_date' => now()->addDays(10)->toDateString(), 'town' => 'Werribee', 'country_code' => 'AU',
        ])->assertRedirect();

        $event = Event::first();
        $this->assertEquals('Office Christmas Party', $event->name);
        $this->assertEquals($business->id, $event->business_id);
        $this->assertEquals(10, strlen($event->slug));
        $this->assertEquals(0, $event->credits_remaining);
    }

    public function test_credit_checkout_creates_pending_purchase_with_gst_and_redirects_to_stripe(): void
    {
        $event = Event::factory()->create();

        $this->mock(StripeGateway::class, function ($mock) {
            $session = new \Stripe\Checkout\Session('cs_credits_1');
            $session->url = 'https://checkout.stripe.com/pay/cs_credits_1';
            $mock->shouldReceive('createCreditCheckout')->once()->andReturn($session);
        });

        $this->actingAs($event->business->user)->post("/host/events/{$event->id}/credits", ['credits' => 100])
            ->assertRedirect('https://checkout.stripe.com/pay/cs_credits_1');

        $purchase = CreditPurchase::first();
        $this->assertEquals(100, $purchase->credits);
        $this->assertEquals(8000, $purchase->amount_cents);
        $this->assertEquals(727, $purchase->gst_cents);
        $this->assertEquals(PaymentStatus::Pending, $purchase->status);
        $this->assertEquals('cs_credits_1', $purchase->stripe_session_id);
        $this->assertEquals(0, $event->fresh()->credits_remaining, 'credits only arrive via the webhook');
    }

    public function test_credit_amount_respects_slider_limits(): void
    {
        $event = Event::factory()->create();
        $user = $event->business->user;
        $this->actingAs($user)->post("/host/events/{$event->id}/credits", ['credits' => 10])->assertSessionHasErrors('credits');
        $this->actingAs($user)->post("/host/events/{$event->id}/credits", ['credits' => 2010])->assertSessionHasErrors('credits');
        $this->actingAs($user)->post("/host/events/{$event->id}/credits", ['credits' => 25])->assertSessionHasErrors('credits');
        $this->assertEquals(0, CreditPurchase::count());
    }

    public function test_webhook_adds_credits_once_and_emails_the_host(): void
    {
        Mail::fake();
        $event = Event::factory()->withCredits(20)->create();
        $purchase = $event->creditPurchases()->create([
            'credits' => 100, 'unit_price_cents' => 80, 'amount_cents' => 8000, 'gst_cents' => 727,
            'stripe_session_id' => 'cs_credits_2', 'status' => PaymentStatus::Pending,
        ]);

        $payload = [
            'id' => 'evt_c', 'object' => 'event', 'type' => 'checkout.session.completed',
            'data' => ['object' => [
                'id' => 'cs_credits_2', 'object' => 'checkout.session', 'payment_status' => 'paid', 'payment_intent' => 'pi_c',
                'invoice' => ['id' => 'in_1', 'hosted_invoice_url' => 'https://invoice.stripe.com/i/in_1'],
                'metadata' => ['type' => 'credits', 'credit_purchase_id' => (string) $purchase->id, 'event_id' => (string) $event->id],
            ]],
        ];

        $this->webhook($payload)->assertOk();
        $this->webhook($payload)->assertOk(); // Stripe retries

        $event->refresh();
        $this->assertEquals(120, $event->credits_purchased);
        $this->assertEquals(120, $event->credits_remaining);

        $purchase->refresh();
        $this->assertEquals(PaymentStatus::Paid, $purchase->status);
        $this->assertEquals('in_1', $purchase->stripe_invoice_id);
        $this->assertEquals('https://invoice.stripe.com/i/in_1', $purchase->invoice_url);

        Mail::assertQueued(PartyPackReadyMail::class, 1);
    }

    public function test_hosts_cannot_touch_other_hosts_events(): void
    {
        $mine = Business::factory()->create();
        $other = Event::factory()->withCredits(50)->create();

        $this->actingAs($mine->user)->get("/host/events/{$other->id}")->assertForbidden();
        $this->actingAs($mine->user)->post("/host/events/{$other->id}/credits", ['credits' => 50])->assertForbidden();
        $this->actingAs($mine->user)->get("/host/events/{$other->id}/letters.csv")->assertForbidden();
        $this->actingAs($mine->user)->post("/host/events/{$other->id}/status", ['action' => 'close'])->assertForbidden();
    }

    public function test_pause_resume_and_close(): void
    {
        $event = Event::factory()->withCredits(50)->create();
        $user = $event->business->user;

        $this->actingAs($user)->post("/host/events/{$event->id}/status", ['action' => 'pause']);
        $this->assertFalse($event->fresh()->isAcceptingLetters());
        $this->actingAs($user)->post("/host/events/{$event->id}/status", ['action' => 'resume']);
        $this->assertTrue($event->fresh()->isAcceptingLetters());
        $this->actingAs($user)->post("/host/events/{$event->id}/status", ['action' => 'close']);
        $this->assertFalse($event->fresh()->isAcceptingLetters());
        $this->actingAs($user)->post("/host/events/{$event->id}/status", ['action' => 'resume']);
        $this->assertEquals(\App\Enums\EventStatus::Closed, $event->fresh()->status);
    }

    public function test_qr_and_poster_downloads(): void
    {
        $event = Event::factory()->withCredits(50)->create();
        $user = $event->business->user;

        $this->actingAs($user)->get("/host/events/{$event->id}/qr.svg")->assertOk()->assertHeader('Content-Type', 'image/svg+xml');
        $png = $this->actingAs($user)->get("/host/events/{$event->id}/qr.png")->assertOk()->assertHeader('Content-Type', 'image/png');
        $this->assertStringStartsWith("\x89PNG", $png->getContent());
        $pdf = $this->actingAs($user)->get("/host/events/{$event->id}/poster.pdf")->assertOk()->assertHeader('Content-Type', 'application/pdf');
        $this->assertStringStartsWith('%PDF', $pdf->getContent());
    }
}
