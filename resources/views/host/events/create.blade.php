@extends('layout')
@section('title', 'Create event - Letters2Santa')

@section('content')
<div class="row justify-content-center"><div class="col-lg-7">
<div class="card p-4 p-md-5">
    <h1 class="text-christmas-red">Create an event</h1>
    <p class="text-muted">You'll choose how many letter credits to buy on the next page.</p>
    @if ($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
    <form method="POST" action="{{ route('host.events.store') }}" enctype="multipart/form-data" class="row g-3">@csrf
        @include('host.events._fields', ['event' => null])
        <div class="col-12 d-flex gap-3 align-items-center">
            <button class="btn btn-christmas">Create event</button>
            <a href="{{ route('host.dashboard') }}" class="small">Cancel</a>
        </div>
    </form>
</div></div></div>
@endsection
