@extends('layout')
@section('title', 'Edit event - Letters2Santa')

@section('content')
<div class="row justify-content-center"><div class="col-lg-7">
<div class="card p-4 p-md-5">
    <h1 class="text-christmas-red">Edit event</h1>
    @if($logoUrl)<img src="{{ $logoUrl }}" alt="" style="max-height:60px;" class="mb-3">@endif
    @if ($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
    <form method="POST" action="{{ route('host.events.update', $event) }}" enctype="multipart/form-data" class="row g-3">@csrf @method('PATCH')
        @include('host.events._fields', ['event' => $event])
        <div class="col-12 d-flex gap-3 align-items-center">
            <button class="btn btn-christmas">Save changes</button>
            <a href="{{ route('host.events.show', $event) }}" class="small">Cancel</a>
        </div>
    </form>
</div></div></div>
@endsection
