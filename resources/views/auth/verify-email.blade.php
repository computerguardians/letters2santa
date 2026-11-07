@extends('layout')
@section('title', 'Verify your email - Letters2Santa')
@section('content')
<div class="row justify-content-center"><div class="col-md-7 col-lg-6"><div class="card p-4 p-md-5 text-center">
    <div style="font-size:3rem;">📬</div>
    <h1 class="text-christmas-red">Check your inbox</h1>
    <p>Thanks for signing up! Before writing to Santa, please verify your email address by clicking the link we just sent to <strong>{{ auth()->user()->email }}</strong>.</p>
    @if (session('status') == 'verification-link-sent')<div class="alert alert-success">A fresh verification link has been sent.</div>@endif
    <div class="d-flex justify-content-center gap-2 flex-wrap">
        <form method="POST" action="{{ route('verification.send') }}">@csrf<button class="btn btn-christmas">Resend verification email</button></form>
        <form method="POST" action="{{ route('logout') }}">@csrf<button class="btn btn-outline-secondary rounded-pill">Log out</button></form>
    </div>
</div></div></div>
@endsection
