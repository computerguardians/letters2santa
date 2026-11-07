<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
  @page { margin: 0; }
  body { margin: 0; font-family: 'DejaVu Serif', Georgia, serif; color: #2b2b2b; }
  .page { position: relative; width: 210mm; height: 297mm; padding: 22mm 24mm; box-sizing: border-box;
          background: #fffaf0; border: 9mm solid #C41E3A; }
  .inner { border: 1.2mm solid #d8b46a; height: 100%; padding: 14mm 16mm; box-sizing: border-box; }
  .kicker { text-align: center; font-size: 10pt; letter-spacing: 3px; text-transform: uppercase; color: #8a7a5a; }
  h1 { text-align: center; font-size: 30pt; color: #C41E3A; margin: 6mm 0 10mm; font-weight: normal; }
  p { font-size: 13pt; line-height: 1.7; margin: 0 0 5mm; }
  .sig { font-size: 26pt; color: #C41E3A; margin-top: 10mm; font-style: italic; }
  .footer { position: absolute; bottom: 14mm; left: 0; right: 0; text-align: center; font-size: 8.5pt; color: #8a7a5a; }
  .seal { position: absolute; top: 18mm; right: 20mm; width: 30mm; height: 30mm; border-radius: 50%;
          background: #165B33; color: #fff; text-align: center; font-size: 8pt; line-height: 1.3; padding-top: 8mm; box-sizing: border-box; }
</style>
</head>
<body>
<div class="page">
  <div class="seal">NORTH POLE<br>OFFICIAL<br>{{ $year }}</div>
  <div class="inner">
    <div class="kicker">From the desk of Santa Claus</div>
    <h1>Merry Christmas, {{ $child }}!</h1>
    @if(($variant ?? 1) === 2)
      <p>Dear {{ $child }},</p>
      <p>Greetings from the North Pole! I have just finished reading your letter from {{ $town }}, and it now has its very own page in the Big Red Book.</p>
      <p>{{ $ageLine }}. I hear that you have been thoughtful and brave this year, and that makes an old man in a red suit very proud indeed.</p>
      <p>Christmas is about the people we love, the fun we share and the kindness we give away for free. You are already very good at all three.</p>
    @elseif(($variant ?? 1) === 3)
      <p>My dear {{ $child }},</p>
      <p>In the middle of the busiest season at the workshop, your letter arrived from {{ $town }}, {{ $country }}. I stopped everything to read it.</p>
      <p>{{ $ageLine }}, and your letter showed me a kind and curious heart. The elves cheered when I read it out loud. Even the grumpy one.</p>
      <p>Be good to yourself, be good to others, and keep that Christmas spirit shining all year.</p>
    @elseif(($variant ?? 1) === 4)
      <p>Dear {{ $child }},</p>
      <p>Your letter from {{ $town }} is open on my lap, and I have read it three times already. I checked the Nice List twice, and there was your name, written in gold ink.</p>
      <p>{{ $ageLine }}, {{ $child }}, and you are growing into someone very special. Thank you for believing.</p>
      <p>Whatever you find under the tree, I hope the best part of today is the people you spend it with.</p>
    @else
      <p>Ho ho ho! Dear {{ $child }},</p>
      <p>Your letter travelled all the way from {{ $town }}, {{ $country }} to my workshop at the North Pole, and I read it by the fire with a cup of cocoa. It was one of my favourites this year.</p>
      <p>{{ $ageLine }}, {{ $child }}. The elves tell me you have been trying your very best to be kind and helpful, and that is exactly the sort of thing that makes my sleigh fly higher.</p>
      <p>Keep being wonderful, and remember that the magic of Christmas lives in kind hearts all year long.</p>
    @endif
    <p>With love and a sprinkle of snow,</p>
    <div class="sig">Santa Claus</div>
  </div>
  <div class="footer">Letters2Santa &middot; letters2santa.com &middot; {{ config('letters.business.legal_name') }}</div>
</div>
</body>
</html>
