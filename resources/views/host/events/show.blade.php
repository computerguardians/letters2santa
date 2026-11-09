@extends('layout')
@section('title', $event->name . ' - Host dashboard - Letters2Santa')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3 text-white">
    <div>
        <div class="small"><a href="{{ route('host.dashboard') }}" class="text-white-50 text-decoration-none">&larr; All events</a></div>
        <h1 class="mb-0" style="text-shadow:2px 2px 4px rgba(0,0,0,.3);">{{ $event->name }}</h1>
        <div class="opacity-75">{{ $event->town }}, {{ \App\Support\Countries::name($event->country_code) }}@if($event->event_date) · {{ $event->event_date->format('l j F Y') }}@endif
            · <span class="badge rounded-pill {{ match($event->status->value) { 'active' => 'bg-success', 'paused' => 'bg-warning text-dark', default => 'bg-secondary' } }}">{{ $event->status->getLabel() }}</span></div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('host.events.edit', $event) }}" class="btn btn-outline-light rounded-pill"><i class="fas fa-pen me-1"></i>Edit</a>
        @if($event->status !== \App\Enums\EventStatus::Closed)
            <form method="POST" action="{{ route('host.events.status', $event) }}">@csrf
                @if($event->status === \App\Enums\EventStatus::Active)
                    <button name="action" value="pause" class="btn btn-warning rounded-pill"><i class="fas fa-pause me-1"></i>Pause</button>
                @else
                    <button name="action" value="resume" class="btn btn-success rounded-pill"><i class="fas fa-play me-1"></i>Resume</button>
                @endif
            </form>
        @endif
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3"><div class="card p-3 text-center h-100"><div class="display-6 fw-bold text-christmas-green">{{ number_format($lettersCount) }}</div><div class="text-muted small">letters received</div></div></div>
    <div class="col-6 col-lg-3"><div class="card p-3 text-center h-100"><div class="display-6 fw-bold {{ $event->credits_remaining === 0 ? 'text-danger' : 'text-christmas-red' }}">{{ number_format($event->credits_remaining) }}</div><div class="text-muted small">credits remaining of {{ number_format($event->credits_purchased) }}</div></div></div>
    <div class="col-12 col-lg-6">
        <div class="card p-3 h-100">
            @if($event->credits_purchased === 0)
                <div class="fw-semibold text-christmas-red"><i class="fas fa-circle-info me-1"></i>Buy credits to activate this event's QR poster.</div>
                <div class="small text-muted">Guests who scan before credits are added will be offered the A${{ number_format(config('letters.letter_price_cents') / 100, 2) }} option instead.</div>
            @else
                <div class="fw-semibold">Party page</div>
                <div class="input-group input-group-sm mt-1" x-data="{ copied: false }">
                    <input class="form-control" value="{{ $event->publicUrl() }}" readonly id="party-url">
                    <button class="btn btn-outline-secondary" type="button" @click="navigator.clipboard.writeText('{{ $event->publicUrl() }}').then(() => { copied = true; setTimeout(() => copied = false, 1500) })"><span x-text="copied ? 'Copied' : 'Copy'"></span></button>
                </div>
                <div class="mt-2 d-flex flex-wrap gap-2">
                    <a href="{{ route('host.events.poster', $event) }}" target="_blank" class="btn btn-sm btn-christmas py-1 px-3"><i class="fas fa-file-pdf me-1"></i>A4 poster (PDF)</a>
                    <a href="{{ route('host.events.qr.png', $event) }}" class="btn btn-sm btn-outline-christmas py-1 px-3"><i class="fas fa-qrcode me-1"></i>QR PNG</a>
                    <a href="{{ route('host.events.qr.svg', $event) }}" class="btn btn-sm btn-outline-christmas py-1 px-3">QR SVG</a>
                    <a href="{{ $event->screenUrl() }}" target="_blank" class="btn btn-sm btn-outline-christmas py-1 px-3"><i class="fas fa-tv me-1"></i>Live screen for a TV</a>
                </div>
            @endif
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-5">
        @if($event->status !== \App\Enums\EventStatus::Closed)
        <div class="card p-4 mb-4" x-data="{ credits: {{ $event->credits_purchased === 0 ? 100 : $credits['min'] }} }">
            <h2 class="fs-4 text-christmas-green">{{ $event->credits_purchased === 0 ? 'Buy letter credits' : 'Top up credits' }}</h2>
            <p class="text-muted small">A${{ number_format($creditPrice, 2) }} per letter, GST included. A tax invoice is emailed after payment.</p>
            @error('credits')<div class="alert alert-danger py-2">{{ $message }}</div>@enderror
            <form method="POST" action="{{ route('host.credits.store', $event) }}">@csrf
                <input type="range" class="form-range" name="credits" min="{{ $credits['min'] }}" max="{{ $credits['max'] }}" step="{{ $credits['step'] }}" x-model.number="credits" aria-label="Number of letters">
                <div class="d-flex justify-content-between align-items-end">
                    <div><div class="fs-3 fw-bold"><span x-text="credits.toLocaleString()"></span> <span class="fs-6 fw-normal text-muted">letters</span></div></div>
                    <div class="text-end"><div class="fs-3 fw-bold text-christmas-green">A$<span x-text="(credits * {{ $creditPrice }}).toFixed(2)"></span></div><div class="text-muted small">incl. GST</div></div>
                </div>
                <div class="d-flex gap-2 mt-2 mb-3 flex-wrap">
                    @foreach([20, 50, 100, 250, 500] as $preset)<button type="button" class="btn btn-sm btn-outline-secondary rounded-pill py-0" @click="credits = {{ $preset }}">{{ $preset }}</button>@endforeach
                </div>
                <button class="btn btn-christmas w-100"><i class="fas fa-lock me-2"></i>Pay with Stripe</button>
            </form>
        </div>
        @endif

        <div class="card p-4 mb-4">
            <h2 class="fs-5 text-christmas-green">Purchases</h2>
            @if($purchases->isEmpty())<div class="text-muted small">No purchases yet.</div>@else
            <table class="table table-sm mb-0 small">
                <thead><tr><th>Date</th><th>Credits</th><th>Amount</th><th>Status</th><th></th></tr></thead>
                <tbody>
                @foreach($purchases as $p)
                    <tr>
                        <td>{{ $p->created_at->timezone(auth()->user()->timezone ?: config('letters.default_timezone'))->format('j M') }}</td>
                        <td>{{ number_format($p->credits) }}</td>
                        <td>A${{ number_format($p->amount_cents / 100, 2) }}</td>
                        <td><span class="badge {{ $p->status->value === 'paid' ? 'bg-success' : ($p->status->value === 'pending' ? 'bg-warning text-dark' : 'bg-secondary') }}">{{ $p->status->getLabel() }}</span></td>
                        <td>@if($p->invoice_url)<a href="{{ $p->invoice_url }}" target="_blank" rel="noopener">Invoice</a>@endif</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            @endif
        </div>

        <div class="card p-4">
            <h2 class="fs-5 text-christmas-green">Poster preview</h2>
            <div class="text-center">
                @if($logoUrl)<img src="{{ $logoUrl }}" alt="" style="max-height:50px;" class="mb-2">@endif
                <div class="santa-font text-christmas-red fs-3">Write to Santa!</div>
                <div class="mx-auto my-2" style="width:160px;">{!! $qrSvg !!}</div>
                <div class="small text-muted">Scan to send your letter to the North Pole</div>
            </div>
            @if($event->status !== \App\Enums\EventStatus::Closed)
            <form method="POST" action="{{ route('host.events.status', $event) }}" class="mt-4 text-center" onsubmit="return confirm('Close this event? Guests will no longer be able to send letters and this cannot be undone. Remaining credits stay on your account for a new event only if you contact us.')">@csrf
                <button name="action" value="close" class="btn btn-sm btn-link text-danger">Close this event permanently</button>
            </form>
            @endif
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="fs-4 text-christmas-green mb-0">Letters received</h2>
                @if($lettersCount)<a href="{{ route('host.events.export', $event) }}" class="btn btn-sm btn-outline-christmas py-1 px-3"><i class="fas fa-file-csv me-1"></i>Export CSV</a>@endif
            </div>
            @if($letters->isEmpty())
                <div class="text-center text-muted py-5"><div style="font-size:2.5rem;">✉️</div>No letters yet. Once guests start scanning, they'll appear here.</div>
            @else
                <div class="list-group list-group-flush">
                @foreach($letters as $letter)
                    @php $thumb = $letter->moderation_status === $approved ? $letter->publicImages()->first() : null; @endphp
                    <div class="list-group-item d-flex align-items-center gap-3 px-0">
                        @if($thumb)
                            <img src="{{ $thumb->thumbUrl() }}" alt="" class="rounded" style="width:56px;height:56px;object-fit:cover;">
                        @else
                            <div class="rounded bg-cream d-flex align-items-center justify-content-center flex-shrink-0" style="width:56px;height:56px;"><i class="fas fa-envelope text-christmas-red"></i></div>
                        @endif
                        <div class="flex-grow-1">
                            <div class="fw-semibold">{{ $letter->child_first_name }}@if($letter->child_age), {{ $letter->child_age }}@endif</div>
                            <div class="text-muted small">{{ $letter->town }} {{ $letter->countryFlag() }} · {{ $letter->created_at->timezone(auth()->user()->timezone ?: config('letters.default_timezone'))->format('j M, g:i a') }}</div>
                        </div>
                        @if($letter->moderation_status->value === 'pending')<span class="badge bg-light text-muted">checking</span>@endif
                    </div>
                @endforeach
                </div>
                <div class="mt-3">{{ $letters->links() }}</div>
                <p class="text-muted small mt-3 mb-0">Guest email addresses are never shown to hosts. Photos appear only after the elves have checked them and never if a face is detected.</p>
            @endif
        </div>
    </div>
</div>
@endsection
