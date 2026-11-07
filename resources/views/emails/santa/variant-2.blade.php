@extends('emails._layout')
@section('title', 'Santa has written back')
@section('header_bg', '#8B0000')
@section('kicker', 'Special Delivery by Reindeer')
@section('heading', 'A letter for '.$child)
@section('content')
<p>Dear {{ $child }},</p>
<p>Greetings from the North Pole! The snow is falling thick and fast outside my window, Rudolph's nose is glowing extra brightly, and I have just finished reading your letter from <strong>{{ $town }}</strong>.</p>
<p>Thank you for writing to me. Every letter that arrives here is stamped by Elf Jingles, read by me, and kept safely in the Big Red Book, and yours now has its very own page.</p>
<p>{{ $ageLine }}. I hear that you have been thoughtful and brave this year, and that makes an old man in a red suit very proud indeed.</p>
<p>Christmas is not only about presents, {{ $child }}. It is about the people we love, the fun we share and the kindness we give away for free. You are already very good at all three.</p>
<p>Leave a carrot out for the reindeer if you can. Dasher gets grumpy without one.</p>
<p>Merry Christmas and a very happy New Year,</p>
<p style="font-size:26px;font-family:'Brush Script MT',cursive;color:#C41E3A;margin:4px 0;">Santa</p>
<p style="color:#777;font-size:14px;">Written at the North Pole, {{ $year }}</p>
<p style="margin-top:28px;padding:14px 18px;background:#f7f1e3;border-radius:10px;font-size:15px;">Your printable letter from Santa is attached to this email.</p>
@endsection
