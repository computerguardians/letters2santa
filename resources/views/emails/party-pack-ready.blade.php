@extends('emails._layout')
@section('title', 'Your party pack is ready')
@section('header_bg', '#165B33')
@section('kicker', 'Letters2Santa party packs')
@section('heading', 'Your party pack is ready!')
@section('content')
<p>Hi {{ $business->contact_name }},</p>
<p>Thank you! <strong>{{ number_format($purchase->credits) }} letter credits</strong> have been added to <strong>{{ $event->name }}</strong>. Here is everything you need for the big day:</p>
<ul style="line-height:1.9;">
  <li><a href="{{ $posterUrl }}">Download the A4 poster (PDF)</a> and print a few copies for the venue.</li>
  <li>Guest page: <a href="{{ $partyUrl }}">{{ $partyUrl }}</a> (this is what the QR code opens).</li>
  <li><a href="{{ $screenUrl }}">Live counter for a TV screen</a> at the party.</li>
  <li><a href="{{ $dashboardUrl }}">Host dashboard</a> to watch letters arrive, top up, pause or export.</li>
</ul>
@if($purchase->invoice_url)
<p>Your tax invoice for A${{ number_format($purchase->amount_cents / 100, 2) }} (including GST of A${{ number_format($purchase->gst_cents / 100, 2) }}) is <a href="{{ $purchase->invoice_url }}">available here</a>.</p>
@else
<p>Your tax invoice for A${{ number_format($purchase->amount_cents / 100, 2) }} (including GST) will arrive from Stripe shortly.</p>
@endif
<p>Every guest who scans the poster writes to Santa free of charge, and Santa replies to their parent's email on Christmas morning. We never share guests' contact details with hosts, and only a first name and town are ever shown publicly.</p>
<p>Merry Christmas from all of us at the North Pole,<br><strong>Elf Jingles</strong><br><span style="color:#777;font-size:14px;">Party pack elf, Letters2Santa</span></p>
@endsection
