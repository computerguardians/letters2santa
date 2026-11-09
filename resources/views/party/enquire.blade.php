@extends('layout')
@section('title', 'Book a demo or get help setting up - Letters2Santa party packs')
@section('meta_description', 'Book a quick demo of Letters2Santa party packs, or ask us to set up your event, poster and QR code for you.')

@section('content')
<div class="row justify-content-center"><div class="col-lg-8">
<div class="card p-4 p-md-5" x-data="{ type: @js(old('type', $type->value)) }">
    <h1 class="text-christmas-red">Talk to us about your party pack</h1>
    <p class="text-muted">Running a Christmas party, carols night, school fete or shop event? Book a 15-minute demo, or let us set the whole thing up for you. Letters cost A${{ number_format($creditPrice, 2) }} each when prepaid as a pack, and there is no charge for a demo or setup help.</p>

    @if ($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif

    <form method="POST" action="{{ route('party.enquire.store') }}">@csrf
        <fieldset class="mb-4">
            <legend class="fs-5 text-christmas-green">How can we help?</legend>
            <div class="row g-2">
                @foreach ($types as $t)
                <div class="col-md-4">
                    <input type="radio" class="btn-check" name="type" id="type-{{ $t->value }}" value="{{ $t->value }}" x-model="type" autocomplete="off">
                    <label class="btn btn-outline-christmas w-100 py-3" for="type-{{ $t->value }}">
                        <i class="fas {{ match($t) { \App\Enums\EnquiryType::Demo => 'fa-video', \App\Enums\EnquiryType::Setup => 'fa-hands-helping', default => 'fa-circle-question' } }} me-1"></i>
                        {{ $t->getLabel() }}
                    </label>
                </div>
                @endforeach
            </div>
            <p class="form-text mt-2" x-show="type === 'demo'">We'll email you to find a time for a short video call and walk you through the poster, guest page and live screen.</p>
            <p class="form-text mt-2" x-show="type === 'setup'" x-cloak>Tell us about your event and we'll create it, prepare your poster and QR code, and send you a link to pay for the credits.</p>
            <p class="form-text mt-2" x-show="type === 'question'" x-cloak>Ask anything about pricing, invoices, privacy or how it works on the day.</p>
        </fieldset>

        <div class="row g-3 mb-4">
            <div class="col-md-7"><label class="form-label" for="organisation">Business or organisation <span class="text-danger">*</span></label>
                <input id="organisation" class="form-control" type="text" name="organisation" value="{{ old('organisation') }}" required maxlength="120" autocomplete="organization"></div>
            <div class="col-md-5"><label class="form-label" for="contact_name">Your name <span class="text-danger">*</span></label>
                <input id="contact_name" class="form-control" type="text" name="contact_name" value="{{ old('contact_name', auth()->user()?->name) }}" required maxlength="100" autocomplete="name"></div>
            <div class="col-md-6"><label class="form-label" for="email">Email <span class="text-danger">*</span></label>
                <input id="email" class="form-control" type="email" name="email" value="{{ old('email', auth()->user()?->email) }}" required maxlength="255" autocomplete="email"></div>
            <div class="col-md-6"><label class="form-label" for="phone">Phone <span class="text-muted">(optional)</span></label>
                <input id="phone" class="form-control" type="tel" name="phone" value="{{ old('phone') }}" maxlength="30" autocomplete="tel"></div>
            <div class="col-md-4"><label class="form-label" for="country_code">Country <span class="text-danger">*</span></label>
                <select id="country_code" name="country_code" class="form-select" required>
                    @foreach ($countries as $code => $name)<option value="{{ $code }}" @selected(old('country_code', 'AU') === $code)>{{ $name }}</option>@endforeach
                </select></div>
            <div class="col-md-4"><label class="form-label" for="event_date">Event date <span class="text-muted">(optional)</span></label>
                <input id="event_date" class="form-control" type="date" name="event_date" value="{{ old('event_date') }}" min="{{ now()->toDateString() }}"></div>
            <div class="col-md-4"><label class="form-label" for="expected_guests">Children expected <span class="text-muted">(optional)</span></label>
                <input id="expected_guests" class="form-control" type="number" name="expected_guests" value="{{ old('expected_guests') }}" min="1" max="100000" inputmode="numeric" placeholder="e.g. 80"></div>
            <div class="col-12"><label class="form-label" for="message">Tell us about your event <span class="text-muted">(optional)</span></label>
                <textarea id="message" class="form-control" name="message" rows="4" maxlength="2000" placeholder="Venue, what's happening on the day, anything you'd like the poster to say…">{{ old('message') }}</textarea></div>
            <div class="d-none" aria-hidden="true"><label for="website">Website</label><input id="website" type="text" name="website" value="" tabindex="-1" autocomplete="off"></div>
        </div>

        @if($turnstileKey)
            <div class="mb-3"><div class="cf-turnstile" data-sitekey="{{ $turnstileKey }}" data-theme="light"></div></div>
        @endif

        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
            <a href="{{ route('host.register') }}" class="small">Happy to do it yourself? Set up a party pack now</a>
            <button class="btn btn-christmas btn-lg"><i class="fas fa-paper-plane me-2"></i>Send enquiry</button>
        </div>
        <p class="form-text mt-3 mb-0">We reply within one business day. Your details are only used to answer this enquiry. See our <a href="{{ route('privacy') }}">privacy policy</a>.</p>
    </form>
</div></div></div>
@endsection

@push('scripts')
@if($turnstileKey)<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>@endif
@endpush
