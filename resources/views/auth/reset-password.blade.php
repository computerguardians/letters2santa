@extends('layout')
@section('title', 'Reset password - Letters2Santa')
@section('content')
<div class="row justify-content-center"><div class="col-md-6 col-lg-5"><div class="card p-4 p-md-5">
    <h1 class="text-christmas-red">Choose a new password</h1>
    <form method="POST" action="{{ route('password.store') }}">@csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">
        <div class="mb-3"><label class="form-label" for="email">Email</label>
            <input id="email" class="form-control @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus>
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
        <div class="mb-3"><label class="form-label" for="password">New password</label>
            <input id="password" class="form-control @error('password') is-invalid @enderror" type="password" name="password" required autocomplete="new-password">
            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
        <div class="mb-3"><label class="form-label" for="password_confirmation">Confirm password</label>
            <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required></div>
        <button class="btn btn-christmas">Reset password</button>
    </form>
</div></div></div>
@endsection
