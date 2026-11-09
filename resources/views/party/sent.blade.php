@extends('layout')
@section('title', 'Off to the North Pole! - Letters2Santa')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-7">
<div class="card p-4 p-md-5 text-center">
    <div style="font-size:4rem;">🎄</div>
    <h1 class="text-christmas-green">Off to the North Pole!</h1>
    @if($letter)
        <p class="lead">{{ $letter->child_first_name }}'s letter is on its way. Elf Jingles will email <strong>{{ $letter->parent_email }}</strong> as soon as it lands on Santa's desk.</p>
        <p class="text-muted">Santa's reply arrives on <strong>{{ $letter->is_late ? 'within the hour' : 'Christmas morning' }}</strong>. Reference: {{ $letter->reference }}</p>
    @else
        <p class="lead">Your letter is on its way. Keep an eye on your inbox for a note from Elf Jingles.</p>
    @endif
    <p class="small text-muted">With thanks to {{ $event->business->name }} for hosting {{ $event->name }}.</p>
    <div class="d-flex flex-wrap gap-2 justify-content-center mt-2">
        @if($event->isAcceptingLetters())
            <a href="{{ route('party.show', $event->slug) }}" class="btn btn-christmas"><i class="fas fa-plus me-2"></i>Send one for a brother or sister</a>
        @endif
        <a href="{{ route('home') }}" class="btn btn-outline-christmas">Back to Letters2Santa</a>
    </div>
</div>
</div>
</div>
@endsection
