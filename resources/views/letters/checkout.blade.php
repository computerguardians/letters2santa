@extends('layout')
@section('title', 'Checkout - Letters2Santa')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-8">
<div class="card p-4 p-md-5">
    <h1 class="text-christmas-red">Ready for the North Pole</h1>
    <p class="text-muted">{{ $letters->count() }} {{ Str::plural('letter', $letters->count()) }} waiting to be sent. Add a brother or sister, or pay now.</p>

    <ul class="list-group list-group-flush mb-4">
        @foreach ($letters as $letter)
            <li class="list-group-item d-flex align-items-center gap-3 py-3">
                @if($img = $letter->images->first())
                    <img src="{{ $img->thumbUrl() }}" alt="" class="rounded" style="width:64px;height:64px;object-fit:cover;">
                @else
                    <div class="rounded bg-cream d-flex align-items-center justify-content-center" style="width:64px;height:64px;"><i class="fas fa-envelope text-christmas-red fs-4"></i></div>
                @endif
                <div class="flex-grow-1">
                    <div class="fw-semibold">{{ $letter->child_first_name }}@if($letter->child_age), {{ $letter->child_age }}@endif</div>
                    <div class="text-muted small">{{ $letter->town }}, {{ $letter->countryName() }} {{ $letter->countryFlag() }} · {{ $letter->body ? Str::limit($letter->body, 60) : 'Picture letter' }}</div>
                </div>
                <div class="fw-semibold">A${{ number_format($unitPrice, 2) }}</div>
                <form method="POST" action="{{ route('letters.destroy', $letter) }}" onsubmit="return confirm('Remove this letter?')">@csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-secondary" title="Remove"><i class="fas fa-times"></i></button>
                </form>
            </li>
        @endforeach
    </ul>

    <div class="d-flex justify-content-between align-items-center border-top pt-3 mb-4">
        <span class="fs-5">Total (incl. GST)</span>
        <span class="fs-3 fw-bold text-christmas-green">A${{ number_format($total, 2) }}</span>
    </div>

    <div class="d-flex flex-wrap gap-3">
        <form method="POST" action="{{ route('checkout.store') }}">@csrf
            <button class="btn btn-christmas"><i class="fas fa-lock me-2"></i>Pay A${{ number_format($total, 2) }} with Stripe</button>
        </form>
        <a href="{{ route('letters.create') }}" class="btn btn-outline-christmas"><i class="fas fa-plus me-2"></i>Add another child</a>
    </div>
    <p class="text-muted small mt-3 mb-0">Card details are entered on Stripe's secure page. Your letters are only sent to the North Pole once payment is confirmed.</p>
</div>
</div>
</div>
@endsection
