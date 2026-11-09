@extends('layout')
@section('title', 'My letters - Letters2Santa')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
    <h1 class="mb-0" style="text-shadow:2px 2px 4px rgba(0,0,0,.3);">My letters</h1>
    <div class="d-flex gap-2">
        @if($unpaidCount)<a href="{{ route('checkout.show') }}" class="btn btn-warning rounded-pill fw-semibold">Pay for {{ $unpaidCount }} unpaid {{ Str::plural('letter', $unpaidCount) }}</a>@endif
        <a href="{{ route('letters.create') }}" class="btn btn-christmas"><i class="fas fa-plus me-2"></i>New letter</a>
    </div>
</div>

@if($letters->isEmpty())
    <div class="card p-5 text-center">
        <div style="font-size:3rem;">✉️</div>
        <h2 class="text-christmas-red">No letters yet</h2>
        <p class="text-muted">Write the first one and Elf Jingles will confirm it arrived at the North Pole.</p>
        <a href="{{ route('letters.create') }}" class="btn btn-christmas mx-auto">Write to Santa</a>
    </div>
@else
<div class="row g-3">
@foreach($letters as $letter)
    <div class="col-md-6">
        <div class="card p-3 h-100">
            <div class="d-flex gap-3">
                @if($img = $letter->images->first())
                    <img src="{{ $img->thumbUrl() }}" alt="" class="rounded" style="width:88px;height:88px;object-fit:cover;">
                @else
                    <div class="rounded bg-cream d-flex align-items-center justify-content-center flex-shrink-0" style="width:88px;height:88px;"><i class="fas fa-envelope-open-text text-christmas-red fs-3"></i></div>
                @endif
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between">
                        <strong class="fs-5">{{ $letter->child_first_name }}</strong>
                        <small class="text-muted">{{ $letter->reference }}</small>
                    </div>
                    <div class="text-muted small">{{ $letter->town }}, {{ $letter->countryName() }} {{ $letter->countryFlag() }} · {{ $letter->created_at->timezone($letter->timezone)->format('j M Y') }}</div>
                    <div class="mt-2 d-flex flex-wrap gap-1">
                        @switch($letter->payment_status->value)
                            @case('paid') @case('credited') <span class="badge bg-success">At the North Pole</span> @break
                            @case('pending') <span class="badge bg-warning text-dark">Unpaid</span> @break
                            @case('refunded') <span class="badge bg-secondary">Refunded</span> @break
                            @default <span class="badge bg-secondary">{{ ucfirst($letter->payment_status->value) }}</span>
                        @endswitch
                        @if($letter->santa_sent_at) <span class="badge bg-danger">Santa replied</span>
                        @elseif($letter->isSettled()) <span class="badge bg-light text-dark border">Santa replies {{ $letter->santa_scheduled_for?->timezone($letter->timezone)->format('j M, g:i a') }}</span> @endif
                        @if($letter->wall_consent && !$letter->consent_withdrawn_at) <span class="badge bg-info text-dark">On the wall</span> @endif
                    </div>
                </div>
            </div>
            @if($letter->body)<p class="small text-muted mt-2 mb-2">{{ Str::limit($letter->body, 140) }}</p>@endif
            <div class="d-flex gap-2 mt-auto pt-2">
                @if(($letter->wall_consent || $letter->instagram_consent) && !$letter->consent_withdrawn_at)
                    <form method="POST" action="{{ route('letters.withdraw-consent', $letter) }}">@csrf
                        <button class="btn btn-sm btn-outline-secondary">Withdraw sharing consent</button>
                    </form>
                @endif
                <form method="POST" action="{{ route('letters.destroy', $letter) }}" onsubmit="return confirm('Delete this letter and its images? Emails already scheduled will still be sent unless the letter is unpaid.')">@csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
@endforeach
</div>
@endif
@endsection
