<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LetterController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/




Route::get('/', [LetterController::class, 'index'])->name('home');
Route::get('/send-letter', [LetterController::class, 'create'])->name('letter.create');
Route::post('/send-letter', [LetterController::class, 'store'])->name('letter.store');
Route::get('/success', [LetterController::class, 'success'])->name('letter.success');

/*
|--------------------------------------------------------------------------
| Stripe Webhook
|--------------------------------------------------------------------------
*/

Route::post('/webhook/stripe', [LetterController::class, 'webhook'])->name('stripe.webhook');

/*
|--------------------------------------------------------------------------
| Order Verification
|--------------------------------------------------------------------------
*/

Route::get('/check-order', [LetterController::class, 'checkOrder'])->name('order.check');
Route::post('/check-order', [LetterController::class, 'verifyOrder'])->name('order.verify');

/*
|--------------------------------------------------------------------------
| Legal Pages
|--------------------------------------------------------------------------
*/

Route::get('/terms', fn() => view('terms'))->name('terms');
Route::get('/privacy', fn() => view('privacy'))->name('privacy');
Route::get('/contact', fn() => view('contact'))->name('contact');

/*
|--------------------------------------------------------------------------
| Admin Routes - Protected by Breeze Auth
|--------------------------------------------------------------------------
*/

// Admin Routes
Route::match(['get', 'post'], '/admin/login', [AdminController::class, 'login'])->name('admin.login');
// Route::get('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');
// Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
// Route::get('/admin/letter/{id}', [AdminController::class, 'view'])->name('admin.view');
// Route::delete('/admin/letter/{id}/photo', [AdminController::class, 'deletePhoto'])->name('admin.delete-photo');

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/letter/{id}', [AdminController::class, 'view'])->name('view');
    Route::delete('/letter/{id}/photo', [AdminController::class, 'deletePhoto'])->name('delete-photo');
    Route::post('/letter/{id}/delivered', [AdminController::class, 'markDelivered'])->name('mark-delivered');

    Route::get('/admin/logout', [AdminController::class, 'logout'])->name('logout');
Route::get('/admin', [AdminController::class, 'dashboard'])->name('dashboard');
Route::get('/admin/letter/{id}', [AdminController::class, 'view'])->name('view');
Route::delete('/admin/letter/{id}/photo', [AdminController::class, 'deletePhoto'])->name('delete-photo');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
/*
|--------------------------------------------------------------------------
| Breeze Authentication Routes
|--------------------------------------------------------------------------
| These routes handle login, logout, password reset, etc.
| Provided by Laravel Breeze
*/

require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Test Endpoints (Remove in Production)
|--------------------------------------------------------------------------
*/

Route::get('/webhook/test', function() {
    return response()->json([
        'status' => 'ok',
        'message' => 'Webhook route is accessible',
        'timestamp' => now()
    ]);
});

Route::post('/webhook/test', function(Request $request) {
    \Log::info('Test webhook received', [
        'headers' => $request->headers->all(),
        'body' => $request->all()
    ]);

    return response()->json([
        'status' => 'received',
        'message' => 'Test webhook logged successfully'
    ]);
});

Route::redirect('/register', '/login');


// Black Friday Special Page
Route::get('/black-friday', [LetterController::class, 'blackFriday'])->name('black-friday');

Route::get('/robots.txt', function () {
    $robots = app()->environment('production')
        ? "User-agent: *\nAllow: /\nSitemap: " . url('sitemap.xml')
        : "User-agent: *\nDisallow: /";

    return response($robots)->header('Content-Type', 'text/plain');
});
