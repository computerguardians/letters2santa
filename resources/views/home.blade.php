@extends('layout')
@section('body-class', 'home')

@section('extra-css')
<style>
    .hero { width: 100vw; margin-left: calc(50% - 50vw); display: grid; grid-template-columns: 1.15fr 1fr; min-height: calc(100vh - 73px); }
    .hero-copy { display: flex; flex-direction: column; justify-content: center; padding: clamp(3rem, 7vw, 6rem) clamp(1.25rem, 6vw, 6rem) clamp(3rem, 7vw, 6rem) max(1.25rem, calc((100vw - 1320px) / 2 + .75rem)); }
    .hero-copy h1 { font-size: clamp(2.8rem, 6.2vw, 5.4rem); font-weight: 700; max-width: 12ch; margin-bottom: 1.25rem; }
    .hero-copy h1 em { font-style: italic; font-weight: 500; }
    .hero-copy .lead { font-size: clamp(1.05rem, 1.4vw, 1.25rem); color: var(--ink-soft); max-width: 46ch; line-height: 1.55; margin-bottom: 1.75rem; }
    .hero-price { display: inline-flex; align-items: baseline; gap: .5rem; font-weight: 700; margin-bottom: 2rem; }
    .hero-price strong { font-family: 'Fraunces', serif; font-size: 2rem; letter-spacing: -.02em; }
    .hero-price span { color: var(--ink-soft); font-weight: 500; }
    .hero-panel { background: var(--christmas-red); position: relative; display: flex; align-items: center; justify-content: center; overflow: hidden; padding: 3rem 1.5rem; }
    .hero-panel::before { content: ""; position: absolute; inset: 0; background: radial-gradient(circle at 30% 20%, rgba(255,255,255,.14), transparent 55%); pointer-events: none; }

    /* Envelope */
    .envelope { position: relative; width: min(440px, 88%); aspect-ratio: 11 / 7; background: #FBF4E8; border-radius: 14px; transform: rotate(-5deg); box-shadow: 0 30px 60px rgba(90,10,20,.35), 0 2px 0 rgba(255,255,255,.4) inset; color: var(--ink); }
    .envelope::before { content: ""; position: absolute; inset: 0; border-radius: 14px; background: repeating-linear-gradient(135deg, var(--christmas-red) 0 14px, transparent 14px 28px, var(--christmas-green) 28px 42px, transparent 42px 56px); -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0); -webkit-mask-composite: xor; mask-composite: exclude; padding: 7px; opacity: .85; }
    .stamp { position: absolute; top: 20px; right: 20px; width: 72px; height: 88px; background: #fff; border: 3px dashed #fff; outline: 2px solid #ddd3c6; outline-offset: -6px; border-radius: 4px; display: flex; flex-direction: column; align-items: center; justify-content: center; font-weight: 800; font-size: .65rem; line-height: 1.1; color: var(--christmas-green); text-align: center; }
    .stamp b { font-family: 'Fraunces', serif; font-size: 1.5rem; color: var(--christmas-red); font-weight: 700; }
    .postmark { position: absolute; top: 26px; right: 96px; width: 78px; height: 78px; border: 2px solid rgba(15,61,46,.55); border-radius: 50%; display: flex; flex-direction: column; align-items: center; justify-content: center; font-size: .55rem; font-weight: 800; letter-spacing: .04em; color: rgba(15,61,46,.7); transform: rotate(-12deg); }
    .postmark b { font-size: .85rem; }
    .address { position: absolute; left: 11%; top: 44%; font-family: 'Fraunces', serif; font-style: italic; font-weight: 500; font-size: clamp(1.05rem, 2.4vw, 1.45rem); line-height: 1.35; }
    .address small { display: block; font-family: 'Manrope', sans-serif; font-style: normal; font-weight: 600; font-size: .7rem; letter-spacing: .02em; color: var(--ink-soft); margin-bottom: .2rem; }
    .seal { position: absolute; left: 50%; bottom: -22px; transform: translateX(-50%); width: 56px; height: 56px; border-radius: 50%; background: radial-gradient(circle at 35% 30%, #F7C85A, var(--christmas-gold) 50%, #C98A1A); box-shadow: 0 6px 14px rgba(0,0,0,.25); display: grid; place-items: center; font-family: 'Fraunces', serif; font-weight: 700; font-size: 1.5rem; color: #7A4B00; }
    .counter-chip { position: absolute; left: 50%; bottom: clamp(1.25rem, 4vw, 2.5rem); transform: translateX(-50%); background: #fff; color: var(--ink); border-radius: 999px; padding: .6rem 1.1rem .6rem .75rem; display: flex; align-items: center; gap: .7rem; box-shadow: 0 10px 30px rgba(90,10,20,.3); white-space: nowrap; text-decoration: none; font-weight: 600; font-size: .9rem; }
    .counter-chip:hover { color: var(--christmas-red); }
    .counter-chip .dot { width: 10px; height: 10px; border-radius: 50%; background: #2BB673; box-shadow: 0 0 0 4px rgba(43,182,115,.25); }
    .counter-chip strong { font-family: 'Fraunces', serif; font-size: 1.25rem; letter-spacing: -.02em; }

    /* Story */
    .story { display: grid; grid-template-columns: 1.75fr 1fr; align-items: stretch; background: var(--warm-cream); border-radius: 28px; overflow: hidden; }
    .story-picture { position: relative; min-height: 100%; }
    .story-picture img { position: absolute; inset: 0; display: block; width: 100%; height: 100%; object-fit: cover; object-position: 50% 40%; }
    .story-text { padding: 2.5rem 2.5rem; display: flex; flex-direction: column; justify-content: center; }
    .story-text h2 { margin-bottom: 1rem; }
    .story-text p { color: var(--ink-soft); line-height: 1.6; font-size: 1rem; margin-bottom: .9rem; max-width: 50ch; }
    .story-text p:last-of-type { margin-bottom: 0; }
    .story-text .signoff { font-family: 'Fraunces', serif; font-style: italic; font-weight: 500; color: var(--christmas-green); font-size: 1.15rem; margin-top: 1.25rem; }

    /* Sections */
    .section { padding: clamp(3rem, 7vw, 6rem) 0 0; }
    .steps { display: grid; grid-template-columns: repeat(3, 1fr); gap: 0; border-top: 1px solid var(--line); border-bottom: 1px solid var(--line); }
    .step { padding: 2.25rem 2rem 2.25rem 0; border-right: 1px solid var(--line); }
    .step:not(:first-child) { padding-left: 2rem; }
    .step:last-child { border-right: 0; }
    .step .n { font-family: 'Fraunces', serif; font-size: 2.5rem; color: var(--christmas-red); line-height: 1; margin-bottom: 1rem; }
    .step h3 { margin-bottom: .5rem; }
    .step p { color: var(--ink-soft); margin: 0; line-height: 1.55; }
    .party { background: var(--christmas-green); color: #fff; border-radius: 28px; padding: clamp(2rem, 5vw, 4rem); display: grid; grid-template-columns: 1.4fr 1fr; gap: 2rem; align-items: center; }
    .party h2 { color: #fff; }
    .party p { color: rgba(255,255,255,.82); font-size: 1.1rem; line-height: 1.55; }
    .party .price { font-family: 'Fraunces', serif; font-size: 2.5rem; color: var(--christmas-gold); letter-spacing: -.02em; }
    .party .btn-christmas { background: #fff; color: var(--christmas-green); }
    .party .btn-christmas:hover { background: var(--christmas-gold); color: var(--christmas-green); }
    .safe { max-width: 62ch; color: var(--ink-soft); line-height: 1.6; }
    .safe h2 { color: var(--ink); font-size: 1.6rem; }
    .cutoff { background: var(--warm-cream); border-left: 4px solid var(--christmas-gold); border-radius: 12px; padding: .9rem 1.1rem; font-weight: 600; margin-bottom: 1.5rem; max-width: 46ch; }

    @media (max-width: 991.98px) {
        .hero { grid-template-columns: 1fr; min-height: 0; }
        .hero-copy { padding: 3rem 1.25rem 2.5rem; }
        .hero-panel { padding: 3rem 1.25rem 5rem; }
        .steps { grid-template-columns: 1fr; }
        .step, .step:not(:first-child) { padding: 1.5rem 0; border-right: 0; border-bottom: 1px solid var(--line); }
        .step:last-child { border-bottom: 0; }
        .party { grid-template-columns: 1fr; }
    }
    @media (max-width: 1199.98px) {
        .story { grid-template-columns: 1fr; }
        .story-picture { aspect-ratio: 16 / 9; min-height: 0; }
        .story-text { padding: 2rem 1.5rem 2.25rem; }
    }
    @media (max-width: 575.98px) {
        .postmark { display: none; }
        .envelope { transform: rotate(-3deg); }
    }
</style>
@endsection

@section('extra-js')
<script>
    // Counts up to the server number on load, then creeps upward slowly while the warm-up seed is in use.
    function letterCounter(target, seeded, cap) {
        return {
            shown: Math.max(0, target - 40),
            start() {
                if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) { this.shown = target; return; }
                const from = this.shown, t0 = performance.now(), dur = 1800;
                const tick = (t) => {
                    const p = Math.min(1, (t - t0) / dur), e = 1 - Math.pow(1 - p, 3);
                    this.shown = Math.round(from + (target - from) * e);
                    if (p < 1) requestAnimationFrame(tick); else if (seeded) this.creep();
                };
                requestAnimationFrame(tick);
            },
            creep() {
                if (this.shown >= cap) return;
                setTimeout(() => { this.shown++; this.creep(); }, 25000 + Math.random() * 50000);
            },
        };
    }
</script>
@endsection

@section('content')
<section class="hero">
    <div class="hero-copy">
        <h1>Write to Santa. <em>Hear back</em> on Christmas morning.</h1>
        <p class="lead">Your child writes a letter or draws a picture. An elf confirms it reached the North Pole straight away, and a personalised letter from Santa arrives in your inbox at 7 am on Christmas Day.</p>
        @if($afterCutoff)
            <div class="cutoff">🎅 It's very close to Christmas. Letters sent now get Santa's reply within the hour.</div>
        @endif
        <div class="hero-price"><strong>A${{ number_format($price, 2) }}</strong><span>per letter, GST included</span></div>
        <div class="d-flex flex-wrap gap-3">
            <a href="{{ auth()->check() ? route('letters.create') : route('register') }}" class="btn btn-christmas btn-lg">Write to Santa</a>
            @if(Route::has('party.landing'))
                <a href="{{ route('party.landing') }}" class="btn btn-outline-christmas btn-lg">Party packs for hosts</a>
            @endif
        </div>
    </div>
    <div class="hero-panel">
        <div class="envelope" aria-hidden="true">
            <div class="postmark"><span>NORTH POLE</span><b>25 DEC</b><span>7:00 AM</span></div>
            <div class="stamp"><b>$1</b>AUSTRALIA</div>
            <div class="address">
                <small>To</small>
                Santa Claus<br>
                1 Reindeer Lane<br>
                North Pole
            </div>
            <div class="seal">S</div>
        </div>
        <a href="{{ Route::has('wall') ? route('wall') : '#' }}" class="counter-chip" x-data="letterCounter({{ (int) $letterCount }}, {{ $counterIsSeeded ? 'true' : 'false' }}, {{ (int) config('letters.wall.seed_counter.target') }})" x-init="start()">
            <span class="dot" aria-hidden="true"></span>
            <strong x-text="shown.toLocaleString()">{{ number_format($letterCount) }}</strong>
            <span>letters reached the North Pole this year</span>
        </a>
    </div>
</section>

<section class="section">
    <div class="story">
        <div class="story-picture">
            <picture>
                <source type="image/webp" srcset="{{ asset('assets/images/hero-santa-postman-900.webp') }} 900w, {{ asset('assets/images/hero-santa-postman.webp') }} 1600w" sizes="(max-width: 991px) 100vw, 60vw">
                <img src="{{ asset('assets/images/hero-santa-postman.jpg') }}" srcset="{{ asset('assets/images/hero-santa-postman-900.jpg') }} 900w, {{ asset('assets/images/hero-santa-postman.jpg') }} 1600w" sizes="(max-width: 991px) 100vw, 60vw" width="1600" height="900" alt="Santa smiling as a postman holds up a sealed letter in a snowy North Pole village" loading="lazy">
            </picture>
        </div>
        <div class="story-text">
            <h2>Every letter makes the same journey</h2>
            <p>The moment you press send, your child's letter leaves the kitchen table and lands in the North Pole mail room, where Elf Jingles stamps it, logs it and sends you a note to say it arrived safely.</p>
            <p>From there it goes out with the afternoon post. Santa reads every single one by the fire, the ones with drawings most of all, and keeps a list of who asked for what and who said thank you.</p>
            <p>Then, on Christmas morning at seven o'clock your time, his reply arrives in your inbox with your child's name on it and a letter you can print and keep.</p>
            <div class="signoff">Warm regards from the North Pole mail room</div>
        </div>
    </div>
</section>

<section class="section">
    <div class="steps">
        @foreach([
            ['Write or draw', 'Type the letter, snap a photo of a drawing or handwritten note, or both. Up to two pictures per letter.'],
            ['Elf Jingles replies', 'Within minutes you get an email confirming the letter arrived safely at the North Pole.'],
            ['Santa writes back', 'On 25 December at 7 am your time, a letter from Santa arrives with a printable keepsake PDF.'],
        ] as $i => [$title, $text])
        <div class="step">
            <div class="n">{{ $i + 1 }}</div>
            <h3>{{ $title }}</h3>
            <p>{{ $text }}</p>
        </div>
        @endforeach
    </div>
</section>

<section class="section">
    <div class="party">
        <div>
            <h2>Hosting a Christmas party?</h2>
            <p class="mb-2">Businesses, schools, councils and venues prepay letter credits. You get a QR poster for the event, guests scan it and send letters free.</p>
            <p class="mb-0">Includes a live letter counter for a TV at the party, and a tax invoice for your records.</p>
        </div>
        <div class="text-lg-end">
            <div class="price">A${{ number_format($creditPrice, 2) }} <span style="font-size:1rem;color:rgba(255,255,255,.75);font-family:'Manrope',sans-serif;">per letter</span></div>
            @if(Route::has('party.landing'))
                <a href="{{ route('party.landing') }}" class="btn btn-christmas mt-3">Get a party pack</a>
            @else
                <span class="badge-trust d-inline-block mt-3">Party packs open late November</span>
            @endif
        </div>
    </div>
</section>

<section class="section safe">
    <h2>Safe by design</h2>
    <p class="mb-0">Only a first name and town are ever shown publicly, and only when you tick the box. Photos are stored privately, location data is stripped from uploads, and every letter is checked before it can appear anywhere. <a href="{{ route('privacy') }}">Read our privacy policy</a>.</p>
</section>
@endsection
