<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Host\BusinessRegistrationController;
use App\Http\Controllers\Host\CreditController;
use App\Http\Controllers\Host\DashboardController;
use App\Http\Controllers\Host\EventController;
use App\Http\Controllers\LetterController;
use App\Http\Controllers\PartyController;
use App\Http\Controllers\PartyEnquiryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Webhooks\StripeWebhookController;
use Illuminate\Support\Facades\Route;

// ----- Public -----
Route::get('/', HomeController::class)->name('home');
Route::view('/terms', 'terms')->name('terms');
Route::view('/privacy', 'privacy')->name('privacy');
Route::view('/contact', 'contact')->name('contact');

// ----- Stripe (CSRF-exempt in bootstrap/app.php) -----
Route::post('/webhook/stripe', StripeWebhookController::class)->name('stripe.webhook');

// ----- Parents (must be logged in with a verified email) -----
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/letters', [LetterController::class, 'index'])->name('letters.index');
    Route::get('/letters/new', [LetterController::class, 'create'])->name('letters.create');
    Route::post('/letters', [LetterController::class, 'store'])->name('letters.store');
    Route::delete('/letters/{letter}', [LetterController::class, 'destroy'])->name('letters.destroy');
    Route::post('/letters/{letter}/withdraw-consent', [LetterController::class, 'withdrawConsent'])->name('letters.withdraw-consent');

    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/checkout/cancel', [CheckoutController::class, 'cancel'])->name('checkout.cancel');
    Route::get('/checkout/{payment}/status', [CheckoutController::class, 'status'])->name('checkout.status');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ----- Party packs: public guest pages -----
Route::get('/party-packs', [PartyController::class, 'landing'])->name('party.landing');
Route::get('/party-packs/enquire', [PartyEnquiryController::class, 'create'])->name('party.enquire');
Route::post('/party-packs/enquire', [PartyEnquiryController::class, 'store'])->middleware('throttle:party-enquiry')->name('party.enquire.store');
Route::get('/party-packs/enquire/thanks', [PartyEnquiryController::class, 'thanks'])->name('party.enquire.thanks');
Route::get('/party/{slug}', [PartyController::class, 'show'])->name('party.show');
Route::post('/party/{slug}', [PartyController::class, 'store'])->middleware('throttle:party-submit')->name('party.store');
Route::get('/party/{slug}/sent', [PartyController::class, 'sent'])->name('party.sent');
Route::get('/party/{slug}/screen', [PartyController::class, 'screen'])->name('party.screen');
Route::get('/party/{slug}/screen.json', [PartyController::class, 'screenJson'])->middleware('throttle:wall-feed')->name('party.screen.json');

// ----- Party hosts (businesses, schools, councils, venues) -----
Route::get('/host/register', [BusinessRegistrationController::class, 'create'])->name('host.register');
Route::post('/host/register', [BusinessRegistrationController::class, 'store'])->middleware('throttle:10,1')->name('host.register.store');

Route::middleware(['auth', 'verified', 'business'])->prefix('host')->name('host.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::patch('/business', [DashboardController::class, 'updateBusiness'])->name('business.update');

    Route::get('/events/new', [EventController::class, 'create'])->name('events.create');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::patch('/events/{event}', [EventController::class, 'update'])->name('events.update');
    Route::post('/events/{event}/status', [EventController::class, 'status'])->name('events.status');
    Route::get('/events/{event}/qr.svg', [EventController::class, 'qrSvg'])->name('events.qr.svg');
    Route::get('/events/{event}/qr.png', [EventController::class, 'qrPng'])->name('events.qr.png');
    Route::get('/events/{event}/poster.pdf', [EventController::class, 'poster'])->name('events.poster');
    Route::get('/events/{event}/letters.csv', [EventController::class, 'export'])->name('events.export');

    Route::post('/events/{event}/credits', [CreditController::class, 'store'])->name('credits.store');
    Route::get('/events/{event}/credits/success', [CreditController::class, 'success'])->name('credits.success');
    Route::get('/events/{event}/credits/cancel', [CreditController::class, 'cancel'])->name('credits.cancel');
    Route::get('/events/{event}/credits/{purchase}/status', [CreditController::class, 'status'])->name('credits.status');
});

// Breeze redirects here after login/registration. Hosts go to their dashboard, parents to their letters.
Route::get('/dashboard', function () {
    return auth()->user()->business
        ? redirect()->route('host.dashboard')
        : redirect()->route('letters.index');
})->middleware('auth')->name('dashboard');

require __DIR__.'/auth.php';

Route::get('/robots.txt', function () {
    $robots = app()->environment('production')
        ? "User-agent: *\nAllow: /\nDisallow: /admin\nDisallow: /letters\nDisallow: /checkout\nDisallow: /host\nDisallow: /party/\n"
        : "User-agent: *\nDisallow: /";

    return response($robots)->header('Content-Type', 'text/plain');
});

// Admin-only email preview used by the Filament "Email previews" page.
Route::get('/admin-tools/email-preview/{template}', \App\Http\Controllers\Admin\EmailPreviewController::class)
    ->middleware('auth')->name('admin.email-preview');
