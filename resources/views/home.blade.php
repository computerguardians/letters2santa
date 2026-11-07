@extends('layout')

@section('content')
<div class="row align-items-center mb-5">
    <div class="col-lg-7 text-white">
        <h1 class="display-3 fw-bold" style="text-shadow:3px 3px 6px rgba(0,0,0,.3);">Write to Santa. <br>Hear back on Christmas morning.</h1>
        <p class="lead mt-3" style="font-size:1.35rem;">Your child writes a letter or draws a picture. An elf confirms it arrived at the North Pole straight away, and a personalised letter from Santa lands in your inbox at 7 am on Christmas Day.</p>
        <p class="fs-5">Only <strong>A${{ number_format($price, 2) }} per letter</strong>, GST included.</p>
        @if($afterCutoff)
            <div class="alert alert-warning d-inline-block">🎅 It's very close to Christmas! Letters sent now get Santa's reply within the hour.</div>
        @endif
        <div class="mt-4 d-flex flex-wrap gap-3">
            <a href="{{ auth()->check() ? route('letters.create') : route('register') }}" class="btn btn-christmas btn-lg"><i class="fas fa-pen-nib me-2"></i>Write to Santa</a>
            @if(Route::has('party.landing'))
                <a href="{{ route('party.landing') }}" class="btn btn-outline-christmas btn-lg">Party packs for businesses</a>
            @endif
        </div>
    </div>
    <div class="col-lg-5 text-center mt-4 mt-lg-0">
        <div class="card p-4">
            <div class="santa-font text-christmas-red" style="font-size:1.6rem;">Letters at the North Pole</div>
            <div class="display-3 fw-bold text-christmas-green" x-data="{ n: {{ (int) $letterCount }} }" x-text="n.toLocaleString()">{{ number_format($letterCount) }}</div>
            <div class="text-muted">and counting, from children all over the world</div>
            @if(Route::has('wall'))<a href="{{ route('wall') }}" class="btn btn-sm btn-outline-christmas mt-3">See the Live Letter Wall</a>@endif
        </div>
    </div>
</div>

<div class="row g-4 mb-5">
    @foreach([
        ['fa-pen-fancy', '1. Write or draw', 'Type the letter, snap a photo of a drawing or handwritten note, or both. Up to two pictures.'],
        ['fa-envelope-open-text', '2. Elf Jingles replies', 'Within minutes you get an email confirming the letter arrived safely at the North Pole.'],
        ['fa-sleigh', '3. Santa writes back', 'On 25 December at 7 am your time, a letter from Santa arrives with a printable keepsake PDF.'],
    ] as [$icon, $title, $text])
    <div class="col-md-4">
        <div class="card h-100 p-4 text-center">
            <i class="fas {{ $icon }} icon-christmas mb-3"></i>
            <h3 class="h4">{{ $title }}</h3>
            <p class="mb-0 text-muted">{{ $text }}</p>
        </div>
    </div>
    @endforeach
</div>

<div class="card p-4 p-md-5 mb-5">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h2 class="text-christmas-green">Hosting a Christmas party?</h2>
            <p class="lead mb-2">Businesses, schools, councils and venues prepay letter credits at <strong>A${{ number_format($creditPrice, 2) }} per letter</strong>. You get a QR poster for the event. Guests scan it and send letters free.</p>
            <p class="text-muted mb-md-0">Includes a live letter counter you can put on a TV at the party, and a tax invoice for your records.</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            @if(Route::has('party.landing'))
                <a href="{{ route('party.landing') }}" class="btn btn-christmas">Get a party pack</a>
            @else
                <span class="badge-trust">Party packs open late November</span>
            @endif
        </div>
    </div>
</div>

<div class="text-center text-white">
    <p class="mb-1"><span class="badge-trust">Safe by design</span></p>
    <p class="mb-0" style="max-width:720px;margin:auto;">Only a first name and town are ever shown publicly, and only when you tick the box. Photos are stored privately, location data is stripped from uploads, and every letter is checked before it can appear anywhere. <a href="{{ route('privacy') }}" class="text-white">Read our privacy policy</a>.</p>
</div>
@endsection
