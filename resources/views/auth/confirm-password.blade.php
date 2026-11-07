@extends('layout')
@section('title', 'Confirm password - Letters2Santa')
@section('content')
<div class="row justify-content-center"><div class="col-md-6 col-lg-5"><div class="card p-4 p-md-5">
    <h1 class="text-christmas-red">Confirm your password</h1>
    <p class="text-muted">This is a secure area. Please confirm your password to continue.</p>
    <form method="POST" action="{{ route('password.confirm') }}">@csrf
        <div class="mb-3"><label class="form-label" for="password">Password</label>
            <input id="password" class="form-control @error('password') is-invalid @enderror" type="password" name="password" required autocomplete="current-password">
            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
        <button class="btn btn-christmas">Confirm</button>
    </form>
</div></div></div>
@endsection
