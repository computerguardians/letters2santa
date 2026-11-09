<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Letters2Santa - Send a letter to Santa, hear back on Christmas morning')</title>
    <meta name="description" content="@yield('meta_description', 'Kids write to Santa, parents pay A$1, and Santa writes back on Christmas morning. Party packs for businesses, schools and venues.')">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,600;0,9..144,700;1,9..144,500&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
    <link rel="shortcut icon" href="/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />
    <link rel="manifest" href="/site.webmanifest" />

    <style>
        :root {
            --christmas-red: #D7263D;
            --christmas-red-deep: #A91B2E;
            --christmas-green: #0F3D2E;
            --christmas-gold: #F2B134;
            --snow-white: #FFFFFF;
            --warm-cream: #FBF4E8;
            --ink: #1A1A1A;
            --ink-soft: #5B5B60;
            --line: #E8E4DE;
        }
        html { scroll-behavior: smooth; }
        body { font-family: 'Manrope', system-ui, sans-serif; color: var(--ink); background: #fff; min-height: 100vh; overflow-x: hidden; -webkit-font-smoothing: antialiased; }
        h1, h2, h3, .santa-font { font-family: 'Fraunces', Georgia, serif; font-weight: 700; letter-spacing: -.02em; line-height: 1.05; }
        h2 { font-size: clamp(1.75rem, 3vw, 2.5rem); }
        h3 { font-size: 1.35rem; }
        .navbar { background: rgba(255,255,255,.92) !important; backdrop-filter: blur(12px); border-bottom: 1px solid var(--line); position: sticky; top: 0; z-index: 20; padding: .75rem 0; }
        .navbar-brand { padding: 0; display: flex; align-items: center; }
        .navbar-brand img { height: 44px; width: auto; display: block; }
        @media (max-width: 575.98px) { .navbar-brand img { height: 36px; } }
        .navbar .nav-link { color: var(--ink) !important; font-weight: 600; font-size: .95rem; padding: .5rem .9rem !important; border-radius: 999px; transition: background .15s; }
        .navbar .nav-link:hover { background: var(--warm-cream); }
        .navbar-toggler { border: 1px solid var(--line); }
        .container-main { margin-top: 40px; margin-bottom: 60px; position: relative; z-index: 2; }
        .home .container-main { margin-top: 0; }
        .card { border: 1px solid var(--line); border-radius: 20px; box-shadow: 0 1px 2px rgba(26,26,26,.04); background: #fff; }
        .btn-christmas { background: var(--christmas-red); border: none; color: white; padding: .9rem 1.8rem; font-size: 1.05rem; font-weight: 700; border-radius: 999px; transition: background .15s, transform .15s; }
        .btn-christmas:hover, .btn-christmas:focus { background: var(--christmas-red-deep); color: white; transform: translateY(-1px); }
        .btn-christmas:disabled { opacity: .6; transform: none; }
        .btn-outline-christmas { border: 2px solid var(--ink); color: var(--ink); padding: .8rem 1.6rem; font-weight: 700; border-radius: 999px; transition: background .15s, color .15s; background: transparent; }
        .btn-outline-christmas:hover { background: var(--ink); color: white; }
        .btn-warning { background: var(--christmas-red); border-color: var(--christmas-red); color: #fff !important; }
        .btn-warning:hover { background: var(--christmas-red-deep); border-color: var(--christmas-red-deep); }
        .footer { background: var(--christmas-green); color: white; padding: 48px 0 36px; margin-top: 80px; position: relative; z-index: 2; }
        .footer a { color: rgba(255,255,255,.9); text-decoration: none; margin: 0 8px; }
        .footer a:hover { color: var(--christmas-gold); }
        .badge-trust { background: var(--warm-cream); color: var(--christmas-green); padding: 8px 15px; border-radius: 999px; font-weight: 700; font-size: .9rem; }
        .icon-christmas { color: var(--christmas-red); font-size: 2.25rem; }
        .form-control, .form-select { border-radius: 12px; padding: .7rem 1rem; border-color: var(--line); }
        .form-control:focus, .form-select:focus { border-color: var(--christmas-green); box-shadow: 0 0 0 .2rem rgba(15,61,46,.12); }
        .form-check-input:checked { background-color: var(--christmas-green); border-color: var(--christmas-green); }
        .text-christmas-red { color: var(--christmas-red); }
        .text-christmas-green { color: var(--christmas-green); }
        .bg-cream { background: var(--warm-cream); }
        a { color: var(--christmas-red); }
        :focus-visible { outline: 3px solid var(--christmas-gold); outline-offset: 2px; }
        [x-cloak] { display: none !important; }
        @media (prefers-reduced-motion: reduce) { *, *::before, *::after { animation: none !important; transition: none !important; } }
    </style>

    @yield('extra-css')
    @stack('head')

    @production
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-JC6L940C7T"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-JC6L940C7T');
    </script>
    <meta name="robots" content="index, follow">
    @else
    <meta name="robots" content="noindex, nofollow">
    @endproduction

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
</head>

<body class="@yield('body-class')">
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}"><img src="{{ asset('assets/images/letters2santa_logo_nav.png') }}" alt="Letters2Santa" width="640" height="136"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-label="Menu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
                    @if(Route::has('wall'))
                        <li class="nav-item"><a class="nav-link" href="{{ route('wall') }}">Letter Wall</a></li>
                    @endif
                    @if(Route::has('party.landing'))
                        <li class="nav-item"><a class="nav-link" href="{{ route('party.landing') }}">Party Packs</a></li>
                    @endif
                    @auth
                        @if(auth()->user()->isBusiness() && Route::has('host.dashboard'))
                            <li class="nav-item"><a class="nav-link" href="{{ route('host.dashboard') }}">Host Dashboard</a></li>
                        @else
                            <li class="nav-item"><a class="nav-link" href="{{ route('letters.index') }}">My Letters</a></li>
                        @endif
                        <li class="nav-item"><a class="nav-link" href="{{ route('profile.edit') }}">Account</a></li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">@csrf
                                <button class="btn btn-link nav-link" type="submit">Log out</button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Log in</a></li>
                        <li class="nav-item ms-lg-2"><a class="btn btn-christmas btn-sm px-3 py-2" href="{{ route('register') }}">Write to Santa</a></li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <div class="container container-main">
        @foreach (['error' => 'danger', 'status' => 'success', 'success' => 'success', 'warning' => 'warning'] as $key => $class)
            @if (session($key))
                <div class="alert alert-{{ $class }} alert-dismissible fade show" role="alert">
                    {{ session($key) }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        @endforeach

        @yield('content')
    </div>

    <footer class="footer">
        <div class="container text-center">
            <p class="mb-2">🔒 Secure payments by Stripe &nbsp;|&nbsp; 🛡️ First name and town only, ever &nbsp;|&nbsp; 🇦🇺 Made in Australia</p>
            <p class="mb-3" style="font-size:.9rem;color:rgba(255,255,255,.8);">
                © {{ date('Y') }} Letters2Santa is operated by {{ config('letters.business.legal_name') }}, ABN {{ config('letters.business.abn') }}. Prices in AUD and include GST.
            </p>
            <p class="mb-0" style="font-size:.85rem;">
                <a href="{{ route('terms') }}">Terms</a> | <a href="{{ route('privacy') }}">Privacy</a> | <a href="{{ route('contact') }}">Contact</a>
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('extra-js')
    @stack('scripts')
</body>
</html>
