@extends('layout')
@section('title', 'Your account - Letters2Santa')
@section('content')
<div class="row justify-content-center"><div class="col-lg-8">
    <h1 class="mb-3">Your account</h1>
    <div class="card p-4 mb-4">@include('profile.partials.update-profile-information-form')</div>
    <div class="card p-4 mb-4">@include('profile.partials.update-password-form')</div>
    <div class="card p-4 mb-4">@include('profile.partials.delete-user-form')</div>
</div></div>
@endsection
