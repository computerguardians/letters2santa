@extends('layout')
@section('title', 'Log in - Letters2Santa')
@section('content')
<div class="row justify-content-center"><div class="col-md-6 col-lg-5">
<div class="card p-4 p-md-5">
    <h1 class="text-christmas-red">Welcome back</h1>
    @if (session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif
    <form method="POST" action="{{ route('login') }}">@csrf
        <div class="mb-3"><label class="form-label" for="email">Email</label>
            <input id="email" class="form-control @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
        <div class="mb-3"><label class="form-label" for="password">Password</label>
            <input id="password" class="form-control @error('password') is-invalid @enderror" type="password" name="password" required autocomplete="current-password">
            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
        <div class="form-check mb-3"><input class="form-check-input" type="checkbox" name="remember" id="remember"><label class="form-check-label" for="remember">Remember me</label></div>
        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('password.request') }}" class="small">Forgot your password?</a>
            <button class="btn btn-christmas">Log in</button>
        </div>
    </form>
    <hr><p class="mb-0 text-center">New here? <a href="{{ route('register') }}">Create a free account</a></p>
</div></div></div>
@endsection
