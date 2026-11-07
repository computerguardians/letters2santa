<?php

namespace App\Services;

use App\Models\CreditPurchase;
use App\Models\Payment;
use Illuminate\Support\Collection;
use Stripe\Checkout\Session;
use Stripe\Event;
use Stripe\Refund;
use Stripe\StripeClient;
use Stripe\Webhook;

/**
 * Thin wrapper around the Stripe SDK so it can be mocked in tests.
 */
class StripeGateway
{
    private ?StripeClient $client = null;

    public function client(): StripeClient
    {
        return $this->client ??= new StripeClient(config('services.stripe.secret'));
    }

    /** One Checkout session for one or more letters (siblings share a transaction fee). */
    public function createLetterCheckout(Payment $payment, Collection $letters, string $successUrl, string $cancelUrl): Session
    {
        $names = $letters->pluck('child_first_name')->implode(', ');

        return $this->client()->checkout->sessions->create([
            'mode' => 'payment',
            'customer_email' => $payment->email,
            'client_reference_id' => (string) $payment->id,
            'line_items' => [[
                'quantity' => $letters->count(),
                'price_data' => [
                    'currency' => config('letters.currency'),
                    'unit_amount' => (int) config('letters.letter_price_cents'),
                    'product_data' => [
                        'name' => 'Letter to Santa',
                        'description' => 'Letter'.($letters->count() > 1 ? 's' : '').' from '.$names.' (includes GST)',
                    ],
                ],
            ]],
            'metadata' => [
                'type' => 'letters',
                'payment_id' => (string) $payment->id,
                'letter_ids' => $letters->pluck('id')->implode(','),
            ],
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
            'expires_at' => now()->addMinutes(30)->timestamp,
        ]);
    }

    /** Credit pack purchase for a party host, with a Stripe tax invoice. */
    public function createCreditCheckout(CreditPurchase $purchase, string $email, string $successUrl, string $cancelUrl): Session
    {
        $event = $purchase->event;

        return $this->client()->checkout->sessions->create([
            'mode' => 'payment',
            'customer_email' => $email,
            'client_reference_id' => 'credits-'.$purchase->id,
            'invoice_creation' => [
                'enabled' => true,
                'invoice_data' => [
                    'description' => "Letters2Santa party pack for \"{$event->name}\". Prices include 10% GST. "
                        .config('letters.business.legal_name').' ABN '.config('letters.business.abn'),
                    'footer' => 'Tax invoice. '.config('letters.business.legal_name').' ABN '.config('letters.business.abn'),
                ],
            ],
            'line_items' => [[
                'quantity' => $purchase->credits,
                'price_data' => [
                    'currency' => $purchase->currency,
                    'unit_amount' => $purchase->unit_price_cents,
                    'product_data' => [
                        'name' => 'Letters2Santa party letter credit',
                        'description' => 'One letter to Santa for a guest at '.$event->name.' (includes GST)',
                    ],
                ],
            ]],
            'metadata' => [
                'type' => 'credits',
                'credit_purchase_id' => (string) $purchase->id,
                'event_id' => (string) $event->id,
            ],
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
            'expires_at' => now()->addMinutes(30)->timestamp,
        ]);
    }

    public function retrieveSession(string $sessionId): Session
    {
        return $this->client()->checkout->sessions->retrieve($sessionId, ['expand' => ['invoice']]);
    }

    public function constructEvent(string $payload, string $signature): Event
    {
        return Webhook::constructEvent($payload, $signature, config('services.stripe.webhook_secret'));
    }

    public function refund(string $paymentIntentId): Refund
    {
        return $this->client()->refunds->create(['payment_intent' => $paymentIntentId]);
    }
}
