<?php

namespace Tests\Feature\Letters;

use App\Enums\PaymentStatus;
use App\Jobs\ModerateLetter;
use App\Jobs\SendElfEmail;
use App\Models\Letter;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class StripeWebhookTest extends TestCase
{
    use RefreshDatabase;

    private function signedPayload(array $event): array
    {
        $payload = json_encode($event);
        $t = time();
        $sig = hash_hmac('sha256', "{$t}.{$payload}", config('services.stripe.webhook_secret'));

        return [$payload, "t={$t},v1={$sig}"];
    }

    private function checkoutCompleted(Payment $payment, string $type = 'letters'): array
    {
        return [
            'id' => 'evt_1', 'object' => 'event', 'type' => 'checkout.session.completed',
            'data' => ['object' => [
                'id' => $payment->stripe_session_id, 'object' => 'checkout.session',
                'payment_status' => 'paid', 'payment_intent' => 'pi_123',
                'metadata' => ['type' => $type, 'payment_id' => (string) $payment->id],
            ]],
        ];
    }

    private function post_webhook(string $payload, string $sig)
    {
        return $this->call('POST', '/webhook/stripe', [], [], [], [
            'HTTP_STRIPE_SIGNATURE' => $sig,
            'CONTENT_TYPE' => 'application/json',
        ], $payload);
    }

    public function test_invalid_signature_is_rejected(): void
    {
        $response = $this->call('POST', '/webhook/stripe', [], [], [], [
            'HTTP_STRIPE_SIGNATURE' => 't=1,v1=bad', 'CONTENT_TYPE' => 'application/json',
        ], '{"type":"checkout.session.completed"}');

        $response->assertStatus(400);
    }

    public function test_completed_checkout_marks_letters_paid_and_queues_elf_email(): void
    {
        Queue::fake();
        $user = User::factory()->timezone('Australia/Melbourne')->create();
        $payment = Payment::create(['user_id' => $user->id, 'email' => $user->email, 'letter_count' => 2, 'amount_cents' => 200, 'stripe_session_id' => 'cs_test_1']);
        $letters = Letter::factory()->count(2)->for($user)->create(['payment_id' => $payment->id, 'timezone' => 'Australia/Melbourne']);

        [$payload, $sig] = $this->signedPayload($this->checkoutCompleted($payment));
        $this->post_webhook($payload, $sig)->assertOk();

        $payment->refresh();
        $this->assertEquals(PaymentStatus::Paid, $payment->status);
        $this->assertEquals('pi_123', $payment->stripe_payment_intent_id);

        foreach ($letters as $letter) {
            $letter->refresh();
            $this->assertEquals(PaymentStatus::Paid, $letter->payment_status);
            $this->assertNotNull($letter->paid_at);
            // 25 Dec 07:00 Melbourne = 24 Dec 20:00 UTC
            $this->assertEquals('2026-12-24 20:00:00', $letter->santa_scheduled_for->utc()->format('Y-m-d H:i:s'));
            $this->assertFalse($letter->is_late);
        }

        Queue::assertPushed(SendElfEmail::class, 2);
        Queue::assertPushed(ModerateLetter::class, 2);
    }

    public function test_webhook_replay_is_idempotent(): void
    {
        Queue::fake();
        $user = User::factory()->create();
        $payment = Payment::create(['user_id' => $user->id, 'email' => $user->email, 'letter_count' => 1, 'amount_cents' => 100, 'stripe_session_id' => 'cs_test_2']);
        Letter::factory()->for($user)->create(['payment_id' => $payment->id]);

        [$payload, $sig] = $this->signedPayload($this->checkoutCompleted($payment));
        $this->post_webhook($payload, $sig)->assertOk();
        $this->post_webhook($payload, $sig)->assertOk();
        $this->post_webhook($payload, $sig)->assertOk();

        Queue::assertPushed(SendElfEmail::class, 1);
        $this->assertEquals(1, Payment::where('status', PaymentStatus::Paid)->count());
    }

    public function test_unpaid_async_session_does_not_settle(): void
    {
        Queue::fake();
        $user = User::factory()->create();
        $payment = Payment::create(['user_id' => $user->id, 'email' => $user->email, 'letter_count' => 1, 'amount_cents' => 100, 'stripe_session_id' => 'cs_test_3']);
        $letter = Letter::factory()->for($user)->create(['payment_id' => $payment->id]);

        $event = $this->checkoutCompleted($payment);
        $event['data']['object']['payment_status'] = 'unpaid';
        [$payload, $sig] = $this->signedPayload($event);
        $this->post_webhook($payload, $sig)->assertOk();

        $this->assertEquals(PaymentStatus::Pending, $letter->fresh()->payment_status);
        Queue::assertNothingPushed();
    }

    public function test_refund_marks_payment_and_letters_refunded_and_removes_wall_consent(): void
    {
        $user = User::factory()->create();
        $payment = Payment::create(['user_id' => $user->id, 'email' => $user->email, 'letter_count' => 1, 'amount_cents' => 100, 'stripe_session_id' => 'cs_4', 'stripe_payment_intent_id' => 'pi_4', 'status' => PaymentStatus::Paid]);
        $letter = Letter::factory()->for($user)->paid()->approved()->wallConsent()->create(['payment_id' => $payment->id]);

        [$payload, $sig] = $this->signedPayload([
            'id' => 'evt_r', 'object' => 'event', 'type' => 'charge.refunded',
            'data' => ['object' => ['id' => 'ch_1', 'object' => 'charge', 'payment_intent' => 'pi_4', 'refunded' => true]],
        ]);
        $this->post_webhook($payload, $sig)->assertOk();

        $this->assertEquals(PaymentStatus::Refunded, $payment->fresh()->status);
        $letter->refresh();
        $this->assertEquals(PaymentStatus::Refunded, $letter->payment_status);
        $this->assertFalse($letter->wall_consent);
        $this->assertFalse($letter->isPubliclyVisible());
    }
}
