<?php

namespace App\Http\Controllers;

use App\Models\Letter;
use App\Services\SantaScheduler;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(SantaScheduler $scheduler): View
    {
        $count = Cache::remember('letters.count', 30, fn () => Letter::settled()->count());

        return view('home', [
            'letterCount' => $count,
            'afterCutoff' => $scheduler->isAfterCutoff(),
            'price' => config('letters.letter_price_cents') / 100,
            'creditPrice' => config('letters.credit_price_cents') / 100,
        ]);
    }
}
