<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex, nofollow">
<title>{{ $event->name }} - Letters to Santa</title>
<link href="https://fonts.googleapis.com/css2?family=Mountains+of+Christmas:wght@700&family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
<style>
  html, body { height: 100%; margin: 0; }
  body { font-family: 'Poppins', sans-serif; color: #fff; background: radial-gradient(circle at 50% 20%, #1f3a5f 0%, #0b1a33 60%, #060e1f 100%); overflow: hidden; }
  .snow { position: fixed; top: -2vh; color: #fff; opacity: .7; animation: fall linear infinite; pointer-events: none; }
  @keyframes fall { to { transform: translateY(104vh); } }
  .wrap { height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; padding: 3vh 4vw; box-sizing: border-box; position: relative; z-index: 2; }
  .event { font-size: 2.2vw; letter-spacing: .3em; text-transform: uppercase; opacity: .8; }
  h1 { font-family: 'Mountains of Christmas', cursive; font-size: 6vw; margin: .5vh 0 2vh; color: #FFD700; text-shadow: 0 4px 20px rgba(0,0,0,.5); }
  .count { font-size: 16vw; line-height: 1; font-weight: 600; color: #fff; text-shadow: 0 6px 30px rgba(0,0,0,.6); transition: transform .3s; }
  .count.bump { transform: scale(1.06); }
  .label { font-size: 2.6vw; margin-top: 1vh; }
  .feed { margin-top: 4vh; display: flex; flex-wrap: wrap; justify-content: center; gap: 1.2vw; max-width: 90vw; }
  .chip { background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.25); border-radius: 999px; padding: 1vh 2vw; font-size: 2vw; backdrop-filter: blur(6px); animation: pop .5s ease; }
  @keyframes pop { from { transform: scale(.7); opacity: 0; } }
  .qr { position: fixed; right: 2vw; bottom: 2vh; background: #fff; padding: 1vw; border-radius: 1vw; text-align: center; color: #165B33; font-size: 1.2vw; z-index: 3; }
  .qr svg { width: 12vw; height: 12vw; display: block; }
  .brand { position: fixed; left: 2vw; bottom: 2vh; font-family: 'Mountains of Christmas', cursive; font-size: 2.4vw; color: #FFD700; z-index: 3; }
</style>
</head>
<body x-data="screen(@json($initial), '{{ route('party.screen.json', $event->slug) }}', {{ $pollSeconds * 1000 }})" x-init="start()">
@for ($i = 0; $i < 30; $i++)
  <div class="snow" style="left:{{ ($i * 37) % 100 }}vw; font-size:{{ 1 + ($i % 4) * .6 }}vw; animation-duration:{{ 10 + ($i * 7) % 16 }}s; animation-delay:-{{ ($i * 3) % 14 }}s;">❄</div>
@endfor
<div class="wrap">
  <div class="event" x-text="data.event"></div>
  <h1>Letters to Santa</h1>
  <div class="count" :class="{bump}" x-text="data.count.toLocaleString()"></div>
  <div class="label">letters from this party have reached the North Pole</div>
  <div class="feed">
    <template x-for="l in data.recent" :key="l.name + l.town + l.ago"><div class="chip"><span x-text="l.name"></span> from <span x-text="l.town"></span> <span x-text="l.flag"></span></div></template>
    <div class="chip" x-show="!data.recent.length" style="opacity:.7;">Scan the poster to send the first letter!</div>
  </div>
</div>
<div class="brand">Letters2Santa</div>
<div class="qr">{!! app(\App\Services\PartyPackService::class)->qrSvg($event, 200) !!}Scan to write to Santa</div>
<script>
function screen(initial, url, every) {
  return {
    data: initial, bump: false,
    start() { setInterval(() => this.tick(), every); },
    async tick() {
      try {
        const r = await fetch(url, {headers: {'Accept': 'application/json'}, cache: 'no-store'});
        const j = await r.json();
        if (j.count !== this.data.count) { this.bump = true; setTimeout(() => this.bump = false, 400); }
        this.data = j;
      } catch (e) {}
    }
  }
}
</script>
</body>
</html>
