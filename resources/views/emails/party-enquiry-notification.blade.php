@extends('emails._layout')
@section('title', 'New party pack enquiry')
@section('header_bg', '#165B33')
@section('kicker', 'Letters2Santa party packs')
@section('heading', $enquiry->type->getLabel())
@section('content')
<p>A prospective host has sent an enquiry from the party packs page. Reply to this email to answer them directly.</p>
<table cellpadding="6" cellspacing="0" style="border-collapse:collapse;width:100%;font-size:15px;">
  <tr><td style="color:#777;width:160px;">Request</td><td><strong>{{ $enquiry->type->getLabel() }}</strong></td></tr>
  <tr><td style="color:#777;">Organisation</td><td>{{ $enquiry->organisation }}</td></tr>
  <tr><td style="color:#777;">Contact</td><td>{{ $enquiry->contact_name }}</td></tr>
  <tr><td style="color:#777;">Email</td><td><a href="mailto:{{ $enquiry->email }}">{{ $enquiry->email }}</a></td></tr>
  <tr><td style="color:#777;">Phone</td><td>{{ $enquiry->phone ?: '—' }}</td></tr>
  <tr><td style="color:#777;">Country</td><td>{{ \App\Support\Countries::name($enquiry->country_code) }}</td></tr>
  <tr><td style="color:#777;">Event date</td><td>{{ $enquiry->event_date?->format('D j M Y') ?: '—' }}</td></tr>
  <tr><td style="color:#777;">Children expected</td><td>{{ $enquiry->expected_guests ? number_format($enquiry->expected_guests) : '—' }}</td></tr>
</table>
@if($enquiry->message)
<p style="margin-top:18px;"><strong>Message</strong></p>
<blockquote style="margin:0;padding:12px 16px;background:#f6f6f6;border-left:4px solid #165B33;white-space:pre-wrap;">{{ $enquiry->message }}</blockquote>
@endif
<p style="margin-top:18px;"><a href="{{ $adminUrl }}">Open in the admin panel</a> to add notes or mark it as contacted.</p>
@endsection
