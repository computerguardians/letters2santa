@extends('layout')
@section('title', 'Payment received - Letters2Santa')

@section('content')
<div class="row justify-content-center"><div class="col-lg-7">
<div class="card p-4 p-md-5 text-center" x-data="poll('{{ route('host.credits.status', [$event, $purchase]) }}', '{{ $purchase->status->value }}')" x-init="start()">
    <template x-if="status === 'paid'">
        <div>
            <div style="font-size:4rem;">🎉</div>
            <h1 class="text-christmas-green">Your party pack is ready!</h1>
            <p class="lead">{{ number_format($purchase->credits) }} letter credits have been added to <strong>{{ $event->name }}</strong>. We've emailed you the links too.</p>
            <div class="d-flex flex-wrap gap-2 justify-content-center mt-3">
                <a href="{{ route('host.events.poster', $event) }}" target="_blank" class="btn btn-christmas"><i class="fas fa-file-pdf me-2"></i>Download the A4 poster</a>
                <a href="{{ route('host.events.show', $event) }}" class="btn btn-outline-christmas">Go to the event</a>
            </div>
            <p class="small text-muted mt-3" x-show="invoice"><a :href="invoice" target="_blank" rel="noopener">View your tax invoice</a></p>
        </div>
    </template>
    <template x-if="status === 'pending'">
        <div>
            <div class="spinner-border text-danger mb-3" role="status"></div>
            <h1 class="text-christmas-red">Confirming your payment…</h1>
            <p class="text-muted">This usually takes a few seconds. Credits are added as soon as Stripe confirms.</p>
        </div>
    </template>
    <template x-if="status === 'failed' || status === 'refunded'">
        <div>
            <h1 class="text-christmas-red">Payment not completed</h1>
            <p>No credits were added.</p>
            <a href="{{ route('host.events.show', $event) }}" class="btn btn-christmas">Back to the event</a>
        </div>
    </template>
</div></div></div>
@endsection

@push('scripts')
<script>
function poll(url, initial) {
    return {
        status: initial, invoice: null, tries: 0,
        start() { if (this.status === 'pending') this.tick(); else this.tick(); },
        async tick() {
            if (this.tries++ > 40) return;
            try { const r = await fetch(url, {headers: {'Accept': 'application/json'}}); const j = await r.json(); this.status = j.status; this.invoice = j.invoice_url; } catch (e) {}
            if (this.status === 'pending') setTimeout(() => this.tick(), 2000);
        }
    }
}
</script>
@endpush
