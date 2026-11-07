@extends('layout')
@section('title', 'Forgot password - Letters2Santa')
@section('content')
<div class="row justify-content-center"><div class="col-md-6 col-lg-5"><div class="card p-4 p-md-5">
    <h1 class="text-christmas-red">Forgot your password?</h1>
    <p class="text-muted">Enter your email and we'll send a reset link.</p>
    @if (session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif
    <form method="POST" action="{{ route('password.email') }}">@csrf
        <div class="mb-3"><label class="form-label" for="email">Email</label>
            <input id="email" class="form-control @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" required autofocus>
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
        <button class="btn btn-christmas">Email reset link</button>
    </form>
</div></div></div>
@endsection
