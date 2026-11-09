@extends('layout')
@section('title', 'Party packs for businesses, schools and venues - Letters2Santa')
@section('meta_description', 'Prepay letters to Santa at A$' . number_format($creditPrice, 2) . ' each, get a QR poster for your Christmas party, and let guests send letters free.')

@section('content')
<div class="row align-items-center mb-5">
    <div class="col-lg-7">
        <h1 class="display-4 fw-bold">Give every child at your party a letter to Santa</h1>
        <p class="lead text-secondary">Businesses, schools, councils, clubs and venues prepay letter credits at <strong>A${{ number_format($creditPrice, 2) }} per letter</strong> instead of A${{ number_format($letterPrice, 2) }}. You get a printable QR poster. Guests scan it, write to Santa on their phone, and hear back from him on Christmas morning. Free for them, paid by you.</p>
        <div class="mt-4 d-flex flex-wrap gap-3">
            <a href="{{ auth()->check() ? route('host.register') : route('host.register') }}" class="btn btn-christmas btn-lg"><i class="fas fa-gift me-2"></i>Set up a party pack</a>
            @auth
                @if(auth()->user()->business)<a href="{{ route('host.dashboard') }}" class="btn btn-outline-christmas btn-lg">Go to host dashboard</a>@endif
            @else
                <a href="{{ route('login') }}" class="btn btn-outline-christmas btn-lg">Host log in</a>
            @endauth
        </div>
    </div>
    <div class="col-lg-5 mt-4 mt-lg-0">
        <div class="card p-4">
            <div class="santa-font text-christmas-red fs-3 mb-3">How it works</div>
            <ol class="mb-0 ps-3" style="line-height:1.9;">
                <li>Register your business and create an event.</li>
                <li>Pick a credit pack with the slider ({{ $credits['min'] }} to {{ number_format($credits['max']) }} letters) and pay by card. A tax invoice is emailed to you.</li>
                <li>Print the A4 poster or show the QR on a screen.</li>
                <li>Guests scan, write, done. A Santa reply lands in their inbox on 25 December.</li>
            </ol>
        </div>
    </div>
</div>

<div class="row g-4 mb-5">
    @foreach ([
        ['fa-qrcode', 'QR poster included', 'A ready-to-print A4 poster with your event name and logo, plus the QR as SVG and PNG for screens and social.'],
        ['fa-tv', 'Live party screen', 'A full-screen counter and name feed you can put on a TV at the venue, updating as letters arrive.'],
        ['fa-chart-simple', 'Host dashboard', 'See letters as they come in, credits remaining, top up any time, pause or close the event, export a CSV.'],
        ['fa-shield-heart', 'Safe by design', 'Only a first name and town are ever shown, and only with the parent\'s tick. Hosts never receive guest contact details.'],
    ] as [$icon, $title, $text])
    <div class="col-md-6 col-lg-3">
        <div class="card p-4 h-100 text-center">
            <i class="fas {{ $icon }} icon-christmas mb-3"></i>
            <h3 class="fs-4 text-christmas-green">{{ $title }}</h3>
            <p class="text-muted mb-0 small">{{ $text }}</p>
        </div>
    </div>
    @endforeach
</div>

<div class="card p-4 p-md-5 mb-5" x-data="{ credits: 100 }">
    <div class="row align-items-center">
        <div class="col-md-7">
            <h2 class="text-christmas-red">What does it cost?</h2>
            <p class="text-muted">Slide to see the price. Prices in AUD and include GST. Unused credits can be used at your next event, or topped up any time.</p>
            <input type="range" class="form-range" min="{{ $credits['min'] }}" max="{{ $credits['max'] }}" step="{{ $credits['step'] }}" x-model.number="credits" aria-label="Number of letters">
        </div>
        <div class="col-md-5 text-center mt-3 mt-md-0">
            <div class="fs-5 text-muted"><span x-text="credits.toLocaleString()"></span> letters</div>
            <div class="display-4 fw-bold text-christmas-green">A$<span x-text="(credits * {{ $creditPrice }}).toFixed(2)"></span></div>
            <a href="{{ route('host.register') }}" class="btn btn-christmas mt-2">Get started</a>
        </div>
    </div>
</div>
@endsection
