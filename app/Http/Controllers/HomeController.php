<?php

namespace App\Http\Controllers;

use App\Services\LetterCounter;
use App\Services\SantaScheduler;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(SantaScheduler $scheduler, LetterCounter $counter): View
    {
        $count = $counter->display();

        return view('home', [
            'letterCount' => $count,
            'counterIsSeeded' => ! $counter->isReal(),
            'afterCutoff' => $scheduler->isAfterCutoff(),
            'price' => config('letters.letter_price_cents') / 100,
            'creditPrice' => config('letters.credit_price_cents') / 100,
        ]);
    }
}
