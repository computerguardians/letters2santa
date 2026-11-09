<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
  @page { margin: 0; }
  body { margin: 0; padding: 0; font-family: 'DejaVu Sans', sans-serif; color: #2b2b2b; }
  /* Content-box sizes: 190 + 2x10 border = 210mm; 277 + 20 = 297mm. DomPDF ignores box-sizing. */
  .page { position: absolute; top: 0; left: 0; width: 190mm; height: 277mm; border: 10mm solid #C41E3A; background: #fffaf0; }
  .inner { position: absolute; top: 8mm; left: 8mm; width: 170mm; height: 257mm; border: 1.5mm solid #d8b46a; text-align: center; }
  .content { padding: 10mm 10mm 0; }
  .logo { height: 24mm; margin-bottom: 3mm; }
  .logo img { max-height: 24mm; max-width: 90mm; }
  .event { font-size: 13pt; letter-spacing: 3px; text-transform: uppercase; color: #8a7a5a; margin-bottom: 2mm; }
  h1 { font-family: 'DejaVu Serif', serif; font-size: 40pt; color: #C41E3A; margin: 2mm 0 1mm; }
  .sub { font-size: 16pt; color: #165B33; margin: 0 0 6mm; }
  .qr img { width: 92mm; height: 92mm; }
  .steps { margin: 6mm auto 0; width: 140mm; text-align: left; font-size: 12pt; line-height: 1.5; border-collapse: collapse; }
  .steps td { vertical-align: top; padding: 1.2mm 2mm; }
  .num { display: inline-block; width: 8mm; height: 8mm; line-height: 8mm; border-radius: 1.5mm; background: #165B33; color: #fff; text-align: center; font-weight: bold; font-size: 11pt; }
  .url { margin-top: 5mm; font-size: 11pt; color: #555; }
  .url strong { color: #165B33; }
  .free { position: absolute; top: -5mm; right: -5mm; width: 26mm; height: 26mm; border-radius: 13mm; background: #FFD700; color: #8B0000; text-align: center; font-weight: bold; font-size: 9.5pt; line-height: 1.25; padding-top: 6mm; }
  .footer { position: absolute; bottom: 6mm; left: 0; width: 170mm; text-align: center; font-size: 8.5pt; color: #8a7a5a; }
  .footer .brand { font-family: 'DejaVu Serif', serif; font-size: 15pt; color: #C41E3A; }
</style>
</head>
<body>
<div class="page">
  <div class="inner">
    <div class="free">FREE<br>at this<br>party</div>
    <div class="content">
      @if($logo)<div class="logo"><img src="{{ $logo }}" alt=""></div>@endif
      <div class="event">{{ $event->name }}</div>
      <h1>Write to Santa!</h1>
      <div class="sub">Scan to send your letter to the North Pole</div>
      <div class="qr"><img src="{{ $qr }}" alt="QR code"></div>
      <table class="steps">
        <tr><td><span class="num">1</span></td><td>Point your phone camera at the code and tap the link.</td></tr>
        <tr><td><span class="num">2</span></td><td>Type your letter to Santa, or snap a photo of your drawing.</td></tr>
        <tr><td><span class="num">3</span></td><td>Santa writes back by email on Christmas morning!</td></tr>
      </table>
      <div class="url">Or visit <strong>{{ $shortUrl }}</strong></div>
    </div>
    <div class="footer">
      <div class="brand">Letters2Santa</div>
      Letters at this event are a gift from {{ $business->name }}. Parents: a first name and town only, ever. letters2santa.com/privacy
    </div>
  </div>
</div>
</body>
</html>
