<?php

namespace App\Http\Controllers\Host;

use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\CreditPurchase;
use App\Models\Event;
use App\Services\StripeGateway;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class CreditController extends Controller
{
    /** Create a pending credit purchase and send the host to Stripe Checkout. */
    public function store(Request $request, Event $event, StripeGateway $stripe): RedirectResponse
    {
        $this->authorizeEvent($request, $event);

        $cfg = config('letters.credits');
        $credits = (int) $request->validate([
            'credits' => ['required', 'integer', 'min:'.$cfg['min'], 'max:'.$cfg['max'], 'multiple_of:'.$cfg['step']],
        ])['credits'];

        if ($event->status === \App\Enums\EventStatus::Closed) {
            return back()->with('error', 'This event is closed. Create a new event to buy more credits.');
        }

        $unit = (int) config('letters.credit_price_cents');
        $amount = $credits * $unit;

        $purchase = $event->creditPurchases()->create([
            'credits' => $credits,
            'unit_price_cents' => $unit,
            'amount_cents' => $amount,
            'gst_cents' => config('letters.business.gst_registered') ? (int) round($amount / 11) : 0,
            'currency' => config('letters.currency'),
            'status' => PaymentStatus::Pending,
        ]);

        try {
            $session = $stripe->createCreditCheckout(
                $purchase,
                $request->user()->email,
                route('host.credits.success', $event).'?session_id={CHECKOUT_SESSION_ID}',
                route('host.credits.cancel', $event),
            );
        } catch (\Throwable $e) {
            Log::error('Stripe credit checkout failed: '.$e->getMessage());
            $purchase->update(['status' => PaymentStatus::Failed]);

            return back()->with('error', 'We could not start the payment. Please try again in a moment.');
        }

        $purchase->update(['stripe_session_id' => $session->id]);

        return redirect()->away($session->url);
    }

    /** Landing after Stripe; the webhook is the source of truth, this page polls. */
    public function success(Request $request, Event $event): View|RedirectResponse
    {
        $this->authorizeEvent($request, $event);

        $purchase = $event->creditPurchases()->where('stripe_session_id', (string) $request->query('session_id'))->first();
        if (! $purchase) {
            return redirect()->route('host.events.show', $event);
        }

        return view('host.credits.success', ['event' => $event, 'purchase' => $purchase]);
    }

    public function cancel(Request $request, Event $event): RedirectResponse
    {
        $this->authorizeEvent($request, $event);

        return redirect()->route('host.events.show', $event)->with('error', 'Payment was cancelled. No credits were added.');
    }

    public function status(Request $request, Event $event, CreditPurchase $purchase): JsonResponse
    {
        $this->authorizeEvent($request, $event);
        abort_unless($purchase->event_id === $event->id, 404);

        return response()->json([
            'status' => $purchase->status->value,
            'credits_remaining' => $event->fresh()->credits_remaining,
            'invoice_url' => $purchase->invoice_url,
        ]);
    }

    private function authorizeEvent(Request $request, Event $event): void
    {
        abort_unless($event->business_id === $request->user()->business?->id, 403);
    }
}
