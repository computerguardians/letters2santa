@extends('emails._layout')
@section('title', $letter->child_first_name."'s letter has arrived")
@section('header_bg', '#165B33')
@section('kicker', 'Elf Mail Room')
@section('heading', 'Your letter has arrived at the North Pole!')
@section('content')
<p>Hi {{ $parentName }},</p>
<p><strong>Elf Jingles here!</strong> I'm writing from the Mail Room at the North Pole, where it is snowing (as usual) and smells of gingerbread (also as usual).</p>
<p>I'm delighted to tell you that <strong>{{ $letter->child_first_name }}'s letter from {{ $letter->town }}</strong> arrived safely at <strong>{{ $arrivedAt }}</strong>. I stamped it, sprinkled it with a pinch of snow and placed it on Santa's reading pile.</p>
@if($thumbBytes)
<p style="text-align:center;margin:24px 0;">
  <img src="{{ $message->embedData($thumbBytes, 'letter-thumbnail.jpg', 'image/jpeg') }}" alt="{{ $letter->child_first_name }}'s letter" style="max-width:260px;border-radius:12px;border:6px solid #fff;box-shadow:0 4px 14px rgba(0,0,0,.15);">
</p>
@elseif(filled($letter->body))
<blockquote style="margin:20px 0;padding:16px 20px;background:#f7f1e3;border-left:4px solid #C41E3A;border-radius:8px;font-style:italic;">{{ \Illuminate\Support\Str::limit($letter->body, 240) }}</blockquote>
@endif
<p>Santa reads every single letter himself. {{ $letter->child_first_name }} will hear back from him on <strong>Christmas morning</strong>{{ $letter->is_late ? ' (your letter arrived close to the big day, so Santa is replying straight away!)' : '' }}.</p>
<p>Your letter reference is <strong>{{ $letter->reference }}</strong>.</p>
<p>Jingle on,<br><strong>Elf Jingles</strong><br><span style="color:#777;font-size:14px;">Head of the North Pole Mail Room</span></p>
@endsection
