@extends('layout')
@section('title', 'Host dashboard - Letters2Santa')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3 text-white">
    <div>
        <h1 class="mb-0" style="text-shadow:2px 2px 4px rgba(0,0,0,.3);">{{ $business->name }}</h1>
        <div class="opacity-75">Host dashboard</div>
    </div>
    <a href="{{ route('host.events.create') }}" class="btn btn-christmas"><i class="fas fa-plus me-2"></i>Create event</a>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3"><div class="card p-3 text-center"><div class="display-6 fw-bold text-christmas-green">{{ number_format($totals['letters']) }}</div><div class="text-muted small">letters sent</div></div></div>
    <div class="col-6 col-md-3"><div class="card p-3 text-center"><div class="display-6 fw-bold text-christmas-red">{{ number_format($totals['credits_remaining']) }}</div><div class="text-muted small">credits remaining</div></div></div>
    <div class="col-12 col-md-6"><div class="card p-3 h-100 d-flex flex-row align-items-center gap-3">
        @if($logoUrl)<img src="{{ $logoUrl }}" alt="" style="max-height:56px;max-width:140px;">@endif
        <div class="small text-muted">Contact {{ $business->contact_name }}@if($business->phone) · {{ $business->phone }}@endif @if($business->abn)· ABN {{ $business->abn }}@endif
            <div><a href="#business" data-bs-toggle="collapse" class="small">Edit business details</a></div></div>
    </div></div>
</div>

<div class="collapse mb-4" id="business">
    <div class="card p-4">
        <h2 class="fs-4 text-christmas-green">Business details</h2>
        <form method="POST" action="{{ route('host.business.update') }}" enctype="multipart/form-data" class="row g-3">@csrf @method('PATCH')
            <div class="col-md-6"><label class="form-label" for="b_name">Name</label><input id="b_name" class="form-control" name="name" value="{{ old('name', $business->name) }}" required></div>
            <div class="col-md-6"><label class="form-label" for="b_abn">ABN</label><input id="b_abn" class="form-control" name="abn" value="{{ old('abn', $business->abn) }}"></div>
            <div class="col-md-6"><label class="form-label" for="b_contact">Contact name</label><input id="b_contact" class="form-control" name="contact_name" value="{{ old('contact_name', $business->contact_name) }}" required></div>
            <div class="col-md-6"><label class="form-label" for="b_phone">Phone</label><input id="b_phone" class="form-control" name="phone" value="{{ old('phone', $business->phone) }}"></div>
            <div class="col-md-6"><label class="form-label" for="b_country">Country</label><select id="b_country" class="form-select" name="country_code">@foreach($countries as $code => $name)<option value="{{ $code }}" @selected($business->country_code === $code)>{{ $name }}</option>@endforeach</select></div>
            <div class="col-md-6"><label class="form-label" for="b_logo">Logo</label><input id="b_logo" class="form-control" type="file" name="logo" accept="image/png,image/jpeg,image/webp">
                @if($business->logo_path)<div class="form-check mt-1"><input class="form-check-input" type="checkbox" name="remove_logo" value="1" id="remove_logo"><label class="form-check-label small" for="remove_logo">Remove current logo</label></div>@endif</div>
            <div class="col-12"><button class="btn btn-outline-christmas">Save</button></div>
        </form>
    </div>
</div>

@if($events->isEmpty())
    <div class="card p-5 text-center">
        <div style="font-size:3rem;">🎉</div>
        <h2 class="text-christmas-red">Create your first event</h2>
        <p class="text-muted">Each event gets its own QR poster, credits and live screen. You can run as many as you like.</p>
        <a href="{{ route('host.events.create') }}" class="btn btn-christmas mx-auto">Create event</a>
    </div>
@else
<div class="row g-3">
@foreach($events as $event)
    <div class="col-md-6">
        <div class="card p-3 h-100">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <a href="{{ route('host.events.show', $event) }}" class="fs-5 fw-semibold text-decoration-none text-christmas-green">{{ $event->name }}</a>
                    <div class="text-muted small">{{ $event->town }}, {{ \App\Support\Countries::name($event->country_code) }}@if($event->event_date) · {{ $event->event_date->format('j M Y') }}@endif</div>
                </div>
                <span class="badge rounded-pill {{ match($event->status->value) { 'active' => 'bg-success', 'paused' => 'bg-warning text-dark', default => 'bg-secondary' } }}">{{ $event->status->getLabel() }}</span>
            </div>
            <div class="d-flex gap-4 mt-3">
                <div><div class="fs-4 fw-bold">{{ number_format($event->letters_count) }}</div><div class="text-muted small">letters</div></div>
                <div><div class="fs-4 fw-bold {{ $event->credits_remaining === 0 ? 'text-danger' : '' }}">{{ number_format($event->credits_remaining) }}</div><div class="text-muted small">credits left</div></div>
            </div>
            <div class="mt-3 d-flex flex-wrap gap-2">
                <a href="{{ route('host.events.show', $event) }}" class="btn btn-sm btn-christmas py-1 px-3">Manage</a>
                @if($event->credits_purchased > 0)
                    <a href="{{ route('host.events.poster', $event) }}" target="_blank" class="btn btn-sm btn-outline-christmas py-1 px-3"><i class="fas fa-file-pdf me-1"></i>Poster</a>
                    <a href="{{ $event->screenUrl() }}" target="_blank" class="btn btn-sm btn-outline-christmas py-1 px-3"><i class="fas fa-tv me-1"></i>Screen</a>
                @endif
            </div>
        </div>
    </div>
@endforeach
</div>
@endif
@endsection
