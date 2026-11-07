@extends('layout')
@section('title', 'Payment received - Letters2Santa')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-7">
<div class="card p-4 p-md-5 text-center" x-data="poll('{{ route('checkout.status', $payment) }}', '{{ $payment->status->value }}')" x-init="start()">
    <template x-if="status === 'paid'">
        <div>
            <div style="font-size:4rem;">🎄</div>
            <h1 class="text-christmas-green">Off to the North Pole!</h1>
            <p class="lead">Payment confirmed. Elf Jingles is stamping {{ $payment->letters->pluck('child_first_name')->join(', ', ' and ') }}'s {{ Str::plural('letter', $payment->letter_count) }} right now and will email you shortly.</p>
            <p class="text-muted">Santa's reply arrives on Christmas morning at 7 am your time. Reference{{ $payment->letter_count > 1 ? 's' : '' }}: {{ $payment->letters->pluck('reference')->join(', ') }}</p>
            <a href="{{ route('letters.index') }}" class="btn btn-christmas mt-2">See my letters</a>
        </div>
    </template>
    <template x-if="status === 'pending'">
        <div>
            <div class="spinner-border text-danger mb-3" role="status"></div>
            <h1 class="text-christmas-red">Confirming your payment…</h1>
            <p class="text-muted">This usually takes a few seconds. You can safely close this page; we'll email you as soon as the letter arrives at the North Pole.</p>
        </div>
    </template>
    <template x-if="status === 'failed' || status === 'refunded'">
        <div>
            <h1 class="text-christmas-red">Payment not completed</h1>
            <p>The payment didn't go through. Your letters are saved.</p>
            <a href="{{ route('checkout.show') }}" class="btn btn-christmas">Try again</a>
        </div>
    </template>
</div>
</div>
</div>
@endsection

@push('scripts')
<script>
function poll(url, initial) {
    return {
        status: initial, tries: 0,
        start() { if (this.status === 'pending') this.tick(); },
        async tick() {
            if (this.tries++ > 40) return;
            try { const r = await fetch(url, {headers: {'Accept': 'application/json'}}); const j = await r.json(); this.status = j.status; } catch (e) {}
            if (this.status === 'pending') setTimeout(() => this.tick(), 2000);
        }
    }
}
</script>
@endpush
