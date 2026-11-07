@extends('emails._layout')
@section('title', "Santa's letter has arrived")
@section('header_bg', '#165B33')
@section('kicker', 'North Pole Workshop')
@section('heading', 'Ho ho ho, '.$child.'!')
@section('content')
<p>My dear {{ $child }},</p>
<p>What a busy season it has been! The elves have been hammering and painting and wrapping day and night, and in the middle of it all your letter arrived from <strong>{{ $town }}, {{ $country }}</strong>. I stopped everything to read it.</p>
<p>I want you to know how much it meant to me. {{ $ageLine }}, and your letter showed me a kind and curious heart. The elves cheered when I read it out loud. Even the grumpy one.</p>
<p>By the time you read this, the sleigh will have landed and taken off again, and Christmas Day will be in full swing in {{ $town }}. I hope it is full of laughter, hugs and at least one very silly game.</p>
<p>Be good to yourself, be good to others, and keep that Christmas spirit shining all year. I will be watching from the North Pole with a very big smile.</p>
<p>Your friend always,</p>
<p style="font-size:26px;font-family:'Brush Script MT',cursive;color:#C41E3A;margin:4px 0;">Santa Claus</p>
<p style="color:#777;font-size:14px;">Christmas {{ $year }}</p>
<p style="margin-top:28px;padding:14px 18px;background:#f7f1e3;border-radius:10px;font-size:15px;">A printable version of this letter is attached. Mrs Claus suggests a frame.</p>
@endsection
