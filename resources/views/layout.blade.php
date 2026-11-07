<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Letters2Santa - Send a letter to Santa, hear back on Christmas morning')</title>
    <meta name="description" content="@yield('meta_description', 'Kids write to Santa, parents pay A$1, and Santa writes back on Christmas morning. Party packs for businesses, schools and venues.')">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Mountains+of+Christmas:wght@400;700&family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
    <link rel="shortcut icon" href="/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />
    <link rel="manifest" href="/site.webmanifest" />

    <style>
        :root {
            --christmas-red: #C41E3A;
            --christmas-green: #165B33;
            --christmas-gold: #FFD700;
            --snow-white: #FFFFFF;
            --warm-cream: #FFF8DC;
        }
        body { font-family: 'Poppins', sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; position: relative; overflow-x: hidden; }
        .snowflake { position: fixed; top: -10px; z-index: 1; color: white; font-size: 1em; animation: fall linear infinite; opacity: .8; pointer-events: none; }
        @keyframes fall { to { transform: translateY(100vh); } }
        h1, h2, h3, .santa-font { font-family: 'Mountains of Christmas', cursive; }
        .navbar { background: rgba(196, 30, 58, .95) !important; backdrop-filter: blur(10px); box-shadow: 0 2px 20px rgba(0,0,0,.1); position: relative; z-index: 10; }
        .navbar-brand { font-family: 'Mountains of Christmas', cursive; font-size: 1.8rem; color: var(--christmas-gold) !important; text-shadow: 2px 2px 4px rgba(0,0,0,.3); }
        .container-main { margin-top: 30px; margin-bottom: 50px; position: relative; z-index: 2; }
        .card { border: none; border-radius: 20px; box-shadow: 0 10px 40px rgba(0,0,0,.15); background: rgba(255,255,255,.98); }
        .btn-christmas { background: linear-gradient(135deg, var(--christmas-red), #8B0000); border: none; color: white; padding: 14px 36px; font-size: 1.15rem; font-weight: 600; border-radius: 50px; box-shadow: 0 5px 15px rgba(196,30,58,.4); transition: all .3s ease; }
        .btn-christmas:hover, .btn-christmas:focus { transform: translateY(-3px); box-shadow: 0 7px 20px rgba(196,30,58,.6); color: white; }
        .btn-christmas:disabled { opacity: .6; transform: none; }
        .btn-outline-christmas { border: 2px solid var(--christmas-green); color: var(--christmas-green); padding: 12px 30px; font-weight: 600; border-radius: 50px; transition: all .3s ease; background: white; }
        .btn-outline-christmas:hover { background: var(--christmas-green); color: white; transform: translateY(-2px); }
        .footer { background: rgba(22, 91, 51, .95); color: white; padding: 30px 0; margin-top: 50px; position: relative; z-index: 2; }
        .footer a { color: rgba(255,255,255,.9); text-decoration: none; margin: 0 8px; }
        .badge-trust { background: var(--christmas-gold); color: var(--christmas-green); padding: 8px 15px; border-radius: 20px; font-weight: 600; }
        .icon-christmas { color: var(--christmas-red); font-size: 3rem; }
        .form-control, .form-select { border-radius: 12px; padding: .7rem 1rem; }
        .form-control:focus, .form-select:focus { border-color: var(--christmas-green); box-shadow: 0 0 0 .2rem rgba(22,91,51,.15); }
        .form-check-input:checked { background-color: var(--christmas-green); border-color: var(--christmas-green); }
        .text-christmas-red { color: var(--christmas-red); }
        .text-christmas-green { color: var(--christmas-green); }
        .bg-cream { background: var(--warm-cream); }
        [x-cloak] { display: none !important; }
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

<body>
    <div class="snowflakes" aria-hidden="true">
        @for ($i = 0; $i < 15; $i++)
            <div class="snowflake" style="left: {{ ($i * 7) % 100 }}%; animation-duration: {{ 12 + ($i * 3) % 18 }}s; animation-delay: {{ ($i * 2) % 10 }}s;">❄</div>
        @endfor
    </div>

    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}"><i class="fas fa-gifts"></i> Letters2Santa</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-label="Menu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item"><a class="nav-link text-white" href="{{ route('home') }}">Home</a></li>
                    @if(Route::has('wall'))
                        <li class="nav-item"><a class="nav-link text-white" href="{{ route('wall') }}">Letter Wall</a></li>
                    @endif
                    @if(Route::has('party.landing'))
                        <li class="nav-item"><a class="nav-link text-white" href="{{ route('party.landing') }}">Party Packs</a></li>
                    @endif
                    @auth
                        @if(auth()->user()->isBusiness() && Route::has('host.dashboard'))
                            <li class="nav-item"><a class="nav-link text-white" href="{{ route('host.dashboard') }}">Host Dashboard</a></li>
                        @else
                            <li class="nav-item"><a class="nav-link text-white" href="{{ route('letters.index') }}">My Letters</a></li>
                        @endif
                        <li class="nav-item"><a class="nav-link text-white" href="{{ route('profile.edit') }}">Account</a></li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">@csrf
                                <button class="btn btn-link nav-link text-white" type="submit">Log out</button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item"><a class="nav-link text-white" href="{{ route('login') }}">Log in</a></li>
                        <li class="nav-item ms-lg-2"><a class="btn btn-warning rounded-pill fw-semibold px-3" href="{{ route('register') }}">Write to Santa</a></li>
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
