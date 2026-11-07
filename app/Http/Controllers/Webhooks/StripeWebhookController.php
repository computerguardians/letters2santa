<?php

namespace App\Http\Controllers\Webhooks;

use App\Actions\SettleLetters;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\CreditPurchase;
use App\Models\Event;
use App\Models\Payment;
use App\Services\StripeGateway;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stripe\Checkout\Session;

/**
 * Stripe is the source of truth for payment. Every handler is idempotent because Stripe retries.
 */
class StripeWebhookController extends Controller
{
    public function __invoke(Request $request, StripeGateway $stripe, SettleLetters $settle): JsonResponse
    {
        try {
            $event = $stripe->constructEvent($request->getContent(), (string) $request->header('Stripe-Signature'));
        } catch (\UnexpectedValueException $e) {
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::warning('Stripe webhook signature failed', ['ip' => $request->ip()]);

            return response()->json(['error' => 'Invalid signature'], 400);
        }

        switch ($event->type) {
            case 'checkout.session.completed':
            case 'checkout.session.async_payment_succeeded':
                $this->handleCheckoutCompleted($event->data->object, $settle);
                break;
            case 'checkout.session.expired':
            case 'checkout.session.async_payment_failed':
                $this->handleCheckoutFailed($event->data->object);
                break;
            case 'charge.refunded':
                $this->handleRefund($event->data->object);
                break;
            default:
                // ignore
        }

        return response()->json(['received' => true]);
    }

    private function handleCheckoutCompleted(Session $session, SettleLetters $settle): void
    {
        if (($session->payment_status ?? 'paid') !== 'paid') {
            return; // async payment method still pending
        }

        $type = $session->metadata->type ?? 'letters';

        if ($type === 'credits') {
            $this->settleCreditPurchase($session);

            return;
        }

        $payment = Payment::where('stripe_session_id', $session->id)->first()
            ?? (isset($session->metadata->payment_id) ? Payment::find($session->metadata->payment_id) : null);

        if (! $payment) {
            Log::warning('Stripe webhook: no payment for session '.$session->id);

            return;
        }

        $paidAt = now();
        $alreadyPaid = ! DB::transaction(function () use ($payment, $session, $paidAt) {
            $fresh = Payment::whereKey($payment->id)->lockForUpdate()->first();
            if ($fresh->status === PaymentStatus::Paid) {
                return false;
            }
            $fresh->update([
                'status' => PaymentStatus::Paid,
                'stripe_session_id' => $session->id,
                'stripe_payment_intent_id' => is_string($session->payment_intent) ? $session->payment_intent : ($session->payment_intent->id ?? null),
                'paid_at' => $paidAt,
            ]);

            return true;
        });

        if ($alreadyPaid) {
            return;
        }

        $settle->handle($payment->letters()->get(), PaymentStatus::Paid, $paidAt);
    }

    private function settleCreditPurchase(Session $session): void
    {
        $purchase = CreditPurchase::where('stripe_session_id', $session->id)->first()
            ?? (isset($session->metadata->credit_purchase_id) ? CreditPurchase::find($session->metadata->credit_purchase_id) : null);

        if (! $purchase) {
            Log::warning('Stripe webhook: no credit purchase for session '.$session->id);

            return;
        }

        DB::transaction(function () use ($purchase, $session) {
            $fresh = CreditPurchase::whereKey($purchase->id)->lockForUpdate()->first();
            if ($fresh->status === PaymentStatus::Paid) {
                return;
            }
            $invoice = $session->invoice ?? null;
            $fresh->update([
                'status' => PaymentStatus::Paid,
                'paid_at' => now(),
                'stripe_payment_intent_id' => is_string($session->payment_intent) ? $session->payment_intent : ($session->payment_intent->id ?? null),
                'stripe_invoice_id' => is_string($invoice) ? $invoice : ($invoice->id ?? null),
                'invoice_url' => is_object($invoice) ? ($invoice->hosted_invoice_url ?? null) : null,
            ]);

            $event = Event::whereKey($fresh->event_id)->lockForUpdate()->first();
            $event->increment('credits_purchased', $fresh->credits);
            $event->increment('credits_remaining', $fresh->credits);
        });
    }

    private function handleCheckoutFailed(Session $session): void
    {
        Payment::where('stripe_session_id', $session->id)
            ->where('status', PaymentStatus::Pending)
            ->update(['status' => PaymentStatus::Failed]);

        CreditPurchase::where('stripe_session_id', $session->id)
            ->where('status', PaymentStatus::Pending)
            ->update(['status' => PaymentStatus::Failed]);
    }

    private function handleRefund(object $charge): void
    {
        $intent = $charge->payment_intent ?? null;
        if (! $intent || ! ($charge->refunded ?? false)) {
            return;
        }

        $payment = Payment::where('stripe_payment_intent_id', $intent)->first();
        if ($payment && $payment->status !== PaymentStatus::Refunded) {
            $payment->update(['status' => PaymentStatus::Refunded, 'refunded_at' => now()]);
            $payment->letters()->update(['payment_status' => PaymentStatus::Refunded->value, 'wall_consent' => false, 'instagram_consent' => false]);
        }

        CreditPurchase::where('stripe_payment_intent_id', $intent)
            ->where('status', '!=', PaymentStatus::Refunded->value)
            ->update(['status' => PaymentStatus::Refunded->value, 'refunded_at' => now()]);
    }
}
