@extends('emails._layout')
@section('title', 'We got your enquiry')
@section('header_bg', '#165B33')
@section('kicker', 'Letters2Santa party packs')
@section('heading', 'Thanks, we\'ll be in touch!')
@section('content')
<p>Hi {{ $enquiry->contact_name }},</p>
@if($enquiry->type === \App\Enums\EnquiryType::Demo)
<p>Thanks for asking about a demo for <strong>{{ $enquiry->organisation }}</strong>. One of our elves will email you within one business day with a few times for a short video call. We'll show you the poster, the guest page and the live screen, and answer any questions.</p>
@elseif($enquiry->type === \App\Enums\EnquiryType::Setup)
<p>Thanks for asking us to set up a party pack for <strong>{{ $enquiry->organisation }}</strong>. One of our elves will email you within one business day to confirm the details. We'll create your event, prepare the poster and QR code, and send you a link to pay for the letter credits.</p>
@else
<p>Thanks for getting in touch about a party pack for <strong>{{ $enquiry->organisation }}</strong>. One of our elves will reply within one business day.</p>
@endif
<p>In the meantime, you can read more about <a href="{{ $landingUrl }}">how party packs work</a>, or if you'd rather not wait, <a href="{{ $registerUrl }}">set one up yourself</a> in a few minutes.</p>
<p>If you need to add anything, just reply to this email or write to <a href="mailto:{{ $supportEmail }}">{{ $supportEmail }}</a>.</p>
<p>Merry Christmas from all of us at the North Pole,<br><strong>Elf Jingles</strong><br><span style="color:#777;font-size:14px;">Party pack elf, Letters2Santa</span></p>
@endsection
