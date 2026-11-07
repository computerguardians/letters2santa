@extends('layout')
@section('title', 'Create your account - Letters2Santa')
@section('content')
<div class="row justify-content-center"><div class="col-md-7 col-lg-6">
<div class="card p-4 p-md-5" x-data="{ tz: '' }" x-init="try { tz = Intl.DateTimeFormat().resolvedOptions().timeZone } catch(e) {}">
    <h1 class="text-christmas-red">Create your account</h1>
    <p class="text-muted">For parents and guardians (18+). Children never need an account.</p>
    <form method="POST" action="{{ route('register') }}">@csrf
        <div class="mb-3"><label class="form-label" for="name">Your name</label>
            <input id="name" class="form-control @error('name') is-invalid @enderror" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
        <div class="mb-3"><label class="form-label" for="email">Email</label>
            <input id="email" class="form-control @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" required autocomplete="username">
            <div class="form-text">Santa's letter will be sent to this address.</div>
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
        <div class="row">
            <div class="col-md-6 mb-3"><label class="form-label" for="password">Password</label>
                <input id="password" class="form-control @error('password') is-invalid @enderror" type="password" name="password" required autocomplete="new-password">
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="col-md-6 mb-3"><label class="form-label" for="password_confirmation">Confirm password</label>
                <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password"></div>
        </div>
        <div class="mb-3"><label class="form-label" for="timezone">Your timezone</label>
            <select id="timezone" name="timezone" class="form-select @error('timezone') is-invalid @enderror" x-model="tz">
                @foreach (timezone_identifiers_list() as $zone)<option value="{{ $zone }}" @selected(old('timezone') === $zone)>{{ str_replace('_', ' ', $zone) }}</option>@endforeach
            </select>
            <div class="form-text">Detected automatically. Santa's letter arrives at 7 am on Christmas Day in this timezone.</div>
            @error('timezone')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
        <div class="form-check mb-2"><input class="form-check-input @error('is_adult') is-invalid @enderror" type="checkbox" name="is_adult" id="is_adult" value="1" required @checked(old('is_adult'))>
            <label class="form-check-label" for="is_adult">I am 18 or over and a parent or guardian.</label>
            @error('is_adult')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror</div>
        <div class="form-check mb-4"><input class="form-check-input @error('terms') is-invalid @enderror" type="checkbox" name="terms" id="terms" value="1" required @checked(old('terms'))>
            <label class="form-check-label" for="terms">I agree to the <a href="{{ route('terms') }}" target="_blank">terms</a> and <a href="{{ route('privacy') }}" target="_blank">privacy policy</a>.</label>
            @error('terms')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror</div>
        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('login') }}" class="small">Already registered?</a>
            <button class="btn btn-christmas">Create account</button>
        </div>
    </form>
</div></div></div>
@endsection
