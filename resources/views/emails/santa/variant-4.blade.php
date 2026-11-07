@extends('emails._layout')
@section('title', 'With love from Santa')
@section('kicker', 'Official Nice List Correspondence')
@section('heading', 'Merry Christmas, '.$child)
@section('content')
<p>Dear {{ $child }},</p>
<p>I am writing to you from my big armchair at the North Pole with Mrs Claus beside me and a very sleepy reindeer at my feet. Your letter from <strong>{{ $town }}</strong> is open on my lap, and I have read it three times already.</p>
<p>I checked the Nice List twice, as I always do, and there was your name, written in gold ink. {{ $ageLine }}, {{ $child }}, and you are growing into someone very special.</p>
<p>The elves asked me to pass on a message: thank you for believing. Belief is what keeps the workshop lights on and the sleigh bells ringing.</p>
<p>Whatever you find under the tree, I hope the best part of today is the people you spend it with. Give someone you love an extra big hug from me.</p>
<p>Until next Christmas,</p>
<p style="font-size:26px;font-family:'Brush Script MT',cursive;color:#C41E3A;margin:4px 0;">Santa</p>
<p style="color:#777;font-size:14px;">The North Pole, Christmas {{ $year }}</p>
<p style="margin-top:28px;padding:14px 18px;background:#f7f1e3;border-radius:10px;font-size:15px;">Your printable letter from Santa is attached.</p>
@endsection
