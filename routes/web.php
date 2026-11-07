<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LetterController;
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

// Breeze redirects here after login/registration.
Route::get('/dashboard', fn () => redirect()->route('letters.index'))->middleware('auth')->name('dashboard');

require __DIR__.'/auth.php';

Route::get('/robots.txt', function () {
    $robots = app()->environment('production')
        ? "User-agent: *\nAllow: /\nDisallow: /admin\nDisallow: /letters\nDisallow: /checkout\n"
        : "User-agent: *\nDisallow: /";

    return response($robots)->header('Content-Type', 'text/plain');
});

// Admin-only email preview used by the Filament "Email previews" page.
Route::get('/admin-tools/email-preview/{template}', \App\Http\Controllers\Admin\EmailPreviewController::class)
    ->middleware('auth')->name('admin.email-preview');
