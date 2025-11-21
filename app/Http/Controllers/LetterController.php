<?php

namespace App\Http\Controllers;

use App\Models\Letter;
use App\Mail\LetterConfirmation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class LetterController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function create()
    {
        return view('letter-form');
    }

    public function store(Request $request)
    {
info('store image');
            // ADD THIS DEBUG CODE
    \Log::info('=== FORM SUBMITTED ===');
    \Log::info('Has file: ' . ($request->hasFile('child_photo') ? 'YES' : 'NO'));
    if ($request->hasFile('child_photo')) {
        \Log::info('File name: ' . $request->file('child_photo')->getClientOriginalName());
        \Log::info('File size: ' . $request->file('child_photo')->getSize() . ' bytes');
        \Log::info('File mime: ' . $request->file('child_photo')->getMimeType());
    }
    \Log::info('All input fields:', $request->except('child_photo'));
    // END DEBUG CODE

        $validated = $request->validate([
            'child_name' => 'required|string|max:255',
            'age_range' => 'required|in:3-5,6-8,9-12',
            'message_to_santa' => 'required|string|max:1000',
            'parent_name' => 'required|string|max:255',
            'parent_email' => 'required|email|max:255',
            'parent_mobile' => 'required|string|max:20',
            'child_photo' => 'nullable|image|mimes:jpeg,jpg,png|max:5120', // 5MB max
        ]);

  // Handle photo upload to S3
    $photoPath = null;
 if ($request->hasFile('child_photo')) {
    $file = $request->file('child_photo');
    $fileName = 'letters/' . uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();

    // Upload to S3 - Store as the filename itself
       Storage::disk('s3')->put($fileName, file_get_contents($file));
    $photoPath = $fileName;

    \Log::info('S3 upload successful! Path: ' . $photoPath);
} else {
        \Log::info('No file uploaded');
    }

        // Create the letter record
        $letter = Letter::create([
            'order_id' => Letter::generateOrderId(),
            'child_name' => $validated['child_name'],
            'age_range' => $validated['age_range'],
            'message_to_santa' => $validated['message_to_santa'],
            'parent_name' => $validated['parent_name'],
            'parent_email' => $validated['parent_email'],
            'parent_mobile' => $validated['parent_mobile'],
            'child_photo' => $photoPath,
            'payment_status' => 'pending',
        ]);

        // Create Stripe Checkout Session
        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'aud',
                        'product_data' => [
                            'name' => 'Magical Letter to Santa - Personalised Storybook',
                            'description' => "For {$letter->child_name} (Age {$letter->age_range})",
                        ],
                        'unit_amount' => 949, // $9.49 in cents
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('letter.success', ['order_id' => $letter->order_id]),
                'cancel_url' => route('letter.create'),
                'client_reference_id' => $letter->order_id,
                'customer_email' => $letter->parent_email,
                'metadata' => [
                    'order_id' => $letter->order_id,
                    'child_name' => $letter->child_name,
                ],
            ]);

            // Save the session ID
            $letter->update([
                'stripe_checkout_session_id' => $session->id,
            ]);

            return redirect($session->url);

        } catch (\Exception $e) {
            return back()->with('error', 'Payment system error. Please try again.');
        }
    }

    public function success(Request $request)
    {
        $orderId = $request->get('order_id');
        $letter = Letter::where('order_id', $orderId)->firstOrFail();

        return view('success', compact('letter'));
    }

    public function webhook(Request $request)
    {
        // Log that webhook was called
        \Log::info('=== STRIPE WEBHOOK RECEIVED ===');
        \Log::info('Headers: ', $request->headers->all());
        \Log::info('Raw Body: ' . $request->getContent());

        Stripe::setApiKey(config('services.stripe.secret'));

        $endpoint_secret = config('services.stripe.webhook_secret');

        // Check if webhook secret is configured
        if (empty($endpoint_secret)) {
            \Log::error('STRIPE_WEBHOOK_SECRET is not set in .env file!');
            return response()->json(['error' => 'Webhook secret not configured'], 500);
        }

        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');

        if (empty($sig_header)) {
            \Log::error('No Stripe-Signature header found in request');
            return response()->json(['error' => 'No signature header'], 400);
        }

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
            \Log::info('Webhook signature verified successfully');
            \Log::info('Event type: ' . $event->type);
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            \Log::error('Invalid webhook payload: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            \Log::error('Invalid webhook signature: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Handle the event
        if ($event->type === 'checkout.session.completed') {
            \Log::info('Processing checkout.session.completed event');

            $session = $event->data->object;
            \Log::info('Session ID: ' . $session->id);

            // Update the letter payment status
            $letter = Letter::where('stripe_checkout_session_id', $session->id)->first();

            if ($letter) {
                \Log::info('Found letter with order ID: ' . $letter->order_id);

                $letter->update([
                    'payment_status' => 'completed',
                    'stripe_payment_intent_id' => $session->payment_intent,
                ]);

                \Log::info('Letter payment status updated to completed');

                // Send confirmation email
                try {
                    Mail::to($letter->parent_email)->send(new LetterConfirmation($letter));
                    \Log::info('Confirmation email sent to: ' . $letter->parent_email);
                } catch (\Exception $e) {
                    // Log error but don't fail the webhook
                    \Log::error('Failed to send confirmation email: ' . $e->getMessage());
                }
            } else {
                \Log::warning('No letter found for session ID: ' . $session->id);
            }
        } else {
            \Log::info('Ignoring event type: ' . $event->type);
        }

        \Log::info('=== WEBHOOK PROCESSING COMPLETE ===');
        return response()->json(['status' => 'success']);
    }



public function checkOrder()
{
    return view('order-check');
}

public function verifyOrder(Request $request)
{
    $validated = $request->validate([
        'order_id' => 'required|string',
        'parent_email' => 'required|email',
    ]);

    // Find the letter by order_id and email
    $letter = Letter::where('order_id', $validated['order_id'])
                    ->where('parent_email', $validated['parent_email'])
                    ->first();

    if (!$letter) {
        return back()
            ->withInput()
            ->with('error', 'Order not found. Please check your Order ID and email address.');
    }

    // Show order status
    return view('order-status', compact('letter'));
}
}
