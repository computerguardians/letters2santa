<?php

namespace App\Http\Controllers;

use App\Enums\PaymentStatus;
use App\Models\Payment;
use App\Services\StripeGateway;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function show(Request $request): View|RedirectResponse
    {
        $letters = $request->user()->letters()->with('images')
            ->where('payment_status', PaymentStatus::Pending)->latest()->get();

        if ($letters->isEmpty()) {
            return redirect()->route('letters.create');
        }

        return view('letters.checkout', [
            'letters' => $letters,
            'unitPrice' => config('letters.letter_price_cents') / 100,
            'total' => $letters->count() * config('letters.letter_price_cents') / 100,
        ]);
    }

    public function store(Request $request, StripeGateway $stripe): RedirectResponse
    {
        $user = $request->user();
        $letters = $user->letters()->where('payment_status', PaymentStatus::Pending)->get();

        if ($letters->isEmpty()) {
            return redirect()->route('letters.create');
        }

        $payment = DB::transaction(function () use ($user, $letters) {
            $payment = Payment::create([
                'user_id' => $user->id,
                'email' => $user->email,
                'letter_count' => $letters->count(),
                'amount_cents' => $letters->count() * (int) config('letters.letter_price_cents'),
                'currency' => config('letters.currency'),
                'status' => PaymentStatus::Pending,
            ]);
            $letters->each->update(['payment_id' => $payment->id]);

            return $payment;
        });

        try {
            $session = $stripe->createLetterCheckout(
                $payment,
                $letters,
                route('checkout.success').'?session_id={CHECKOUT_SESSION_ID}',
                route('checkout.cancel'),
            );
        } catch (\Throwable $e) {
            Log::error('Stripe checkout failed: '.$e->getMessage());

            return back()->with('error', 'We could not start the payment. Please try again in a moment.');
        }

        $payment->update(['stripe_session_id' => $session->id]);

        return redirect()->away($session->url);
    }

    /** Landing page after Stripe. The webhook is the source of truth; this page just polls the status. */
    public function success(Request $request): View|RedirectResponse
    {
        $payment = Payment::where('stripe_session_id', (string) $request->query('session_id'))
            ->where('user_id', $request->user()->id)->first();

        if (! $payment) {
            return redirect()->route('letters.index');
        }

        return view('letters.success', ['payment' => $payment->load('letters')]);
    }

    public function cancel(): RedirectResponse
    {
        return redirect()->route('checkout.show')->with('error', 'Payment was cancelled. Your letters are saved and you can pay whenever you are ready.');
    }

    public function status(Request $request, Payment $payment): JsonResponse
    {
        abort_unless($payment->user_id === $request->user()->id, 403);

        return response()->json(['status' => $payment->status->value]);
    }
}
