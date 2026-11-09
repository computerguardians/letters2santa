@extends('layout')
@section('title', 'Register your business - Letters2Santa party packs')

@section('content')
<div class="row justify-content-center"><div class="col-lg-7">
<div class="card p-4 p-md-5" x-data="{ tz: '' }" x-init="try { tz = Intl.DateTimeFormat().resolvedOptions().timeZone } catch(e) {}">
    <h1 class="text-christmas-red">Set up your party pack</h1>
    <p class="text-muted">For businesses, schools, councils, clubs and venues. Letters cost A${{ number_format(config('letters.credit_price_cents') / 100, 2) }} each when prepaid as a pack.</p>

    @if ($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif

    <form method="POST" action="{{ route('host.register.store') }}" enctype="multipart/form-data">@csrf
        <h2 class="fs-4 text-christmas-green mt-2">Your organisation</h2>
        <div class="row g-3 mb-4">
            <div class="col-md-7"><label class="form-label" for="business_name">Business or organisation name <span class="text-danger">*</span></label>
                <input id="business_name" class="form-control" type="text" name="business_name" value="{{ old('business_name') }}" required maxlength="120"></div>
            <div class="col-md-5"><label class="form-label" for="abn">ABN <span class="text-muted">(optional)</span></label>
                <input id="abn" class="form-control" type="text" name="abn" value="{{ old('abn') }}" inputmode="numeric" placeholder="11 digits"></div>
            <div class="col-md-6"><label class="form-label" for="contact_name">Contact name <span class="text-danger">*</span></label>
                <input id="contact_name" class="form-control" type="text" name="contact_name" value="{{ old('contact_name', $existingUser?->name) }}" required maxlength="100" autocomplete="name"></div>
            <div class="col-md-6"><label class="form-label" for="phone">Phone <span class="text-muted">(optional)</span></label>
                <input id="phone" class="form-control" type="tel" name="phone" value="{{ old('phone') }}" maxlength="30" autocomplete="tel"></div>
            <div class="col-md-6"><label class="form-label" for="country_code">Country <span class="text-danger">*</span></label>
                <select id="country_code" name="country_code" class="form-select" required>
                    @foreach ($countries as $code => $name)<option value="{{ $code }}" @selected(old('country_code', 'AU') === $code)>{{ $name }}</option>@endforeach
                </select></div>
            <div class="col-md-6"><label class="form-label" for="logo">Logo for your poster <span class="text-muted">(optional)</span></label>
                <input id="logo" class="form-control" type="file" name="logo" accept="image/png,image/jpeg,image/webp">
                <div class="form-text">PNG or JPG. You can add it later too.</div></div>
        </div>

        @if(! $existingUser)
            <h2 class="fs-4 text-christmas-green">Your login</h2>
            <div class="row g-3 mb-4">
                <div class="col-md-12"><label class="form-label" for="email">Email <span class="text-danger">*</span></label>
                    <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autocomplete="username">
                    <div class="form-text">Your tax invoice and party pack go here.</div></div>
                <div class="col-md-6"><label class="form-label" for="password">Password <span class="text-danger">*</span></label>
                    <input id="password" class="form-control" type="password" name="password" required autocomplete="new-password"></div>
                <div class="col-md-6"><label class="form-label" for="password_confirmation">Confirm password <span class="text-danger">*</span></label>
                    <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password"></div>
                <div class="col-12"><label class="form-label" for="timezone">Timezone</label>
                    <select id="timezone" name="timezone" class="form-select" x-model="tz">
                        @foreach (timezone_identifiers_list() as $zone)<option value="{{ $zone }}" @selected(old('timezone') === $zone)>{{ str_replace('_', ' ', $zone) }}</option>@endforeach
                    </select></div>
            </div>
        @else
            <div class="alert alert-info">Adding a business profile to your existing account <strong>{{ $existingUser->email }}</strong>.</div>
        @endif

        <div class="form-check mb-4"><input class="form-check-input" type="checkbox" name="terms" id="terms" value="1" required @checked(old('terms'))>
            <label class="form-check-label" for="terms">I agree to the <a href="{{ route('terms') }}" target="_blank">terms</a> and <a href="{{ route('privacy') }}" target="_blank">privacy policy</a>, and I understand guests' contact details are never shared with hosts.</label></div>

        <div class="d-flex justify-content-between align-items-center">
            @guest<a href="{{ route('login') }}" class="small">Already registered? Log in</a>@else<span></span>@endguest
            <button class="btn btn-christmas">Create host account</button>
        </div>
    </form>
</div></div></div>
@endsection
