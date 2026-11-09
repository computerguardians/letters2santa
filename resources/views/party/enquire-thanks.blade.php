@extends('layout')
@section('title', 'Thanks, we will be in touch - Letters2Santa')

@section('content')
<div class="row justify-content-center"><div class="col-lg-7">
<div class="card p-4 p-md-5 text-center">
    <i class="fas fa-envelope-circle-check icon-christmas mb-3" style="font-size:3rem;"></i>
    <h1 class="text-christmas-red">Thanks, we've got your enquiry</h1>
    @if($type === \App\Enums\EnquiryType::Demo)
        <p class="lead text-secondary">We'll email you within one business day with a few times for a short demo. Keep an eye on your inbox (and the junk folder, just in case).</p>
    @elseif($type === \App\Enums\EnquiryType::Setup)
        <p class="lead text-secondary">We'll email you within one business day to confirm the details, set up your event and poster, and send you a link to pay for the credits.</p>
    @else
        <p class="lead text-secondary">We'll get back to you within one business day.</p>
    @endif
    <p class="text-muted">A confirmation has been sent to your email. If you'd rather not wait, you can <a href="{{ route('host.register') }}">set up a party pack yourself</a> in a few minutes.</p>
    <div class="mt-3"><a href="{{ route('party.landing') }}" class="btn btn-outline-christmas"><i class="fas fa-arrow-left me-1"></i> Back to party packs</a></div>
</div></div></div>
@endsection
