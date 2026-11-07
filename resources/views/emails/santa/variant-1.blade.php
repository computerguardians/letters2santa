@extends('emails._layout')
@section('title', 'A letter from Santa')
@section('kicker', 'From the desk of Santa Claus')
@section('heading', 'Merry Christmas, '.$child.'!')
@section('content')
<p>Ho ho ho! Dear {{ $child }},</p>
<p>Your letter travelled all the way from <strong>{{ $town }}, {{ $country }}</strong> to my workshop at the North Pole, and I read it by the fire with a cup of cocoa and a plate of Mrs Claus's shortbread. It was one of my favourites this year.</p>
<p>{{ $ageLine }}, {{ $child }}. The elves tell me you have been trying your very best to be kind and helpful, and that is exactly the sort of thing that makes my sleigh fly higher.</p>
<p>Tonight the reindeer are fed, the sleigh is polished and the sack is packed. If you listen very carefully on Christmas Eve you may hear a jingle over {{ $town }}. That will be us!</p>
<p>Keep being wonderful, keep looking after the people around you, and remember that the magic of Christmas lives in kind hearts all year long.</p>
<p>With love and a sprinkle of snow,</p>
<p style="font-size:26px;font-family:'Brush Script MT',cursive;color:#C41E3A;margin:4px 0;">Santa Claus</p>
<p style="color:#777;font-size:14px;">The North Pole, {{ $year }}</p>
<p style="margin-top:28px;padding:14px 18px;background:#f7f1e3;border-radius:10px;font-size:15px;">A printable copy of this letter is attached, so it can go on the fridge or under the tree.</p>
@endsection
