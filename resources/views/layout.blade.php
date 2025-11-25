<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Letters2Santa - Magical Christmas Stories')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Mountains+of+Christmas:wght@400;700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        /* Snowflake animation */
        .snowflake {
            position: fixed;
            top: -10px;
            z-index: 9999;
            color: white;
            font-size: 1em;
            animation: fall linear infinite;
            opacity: 0.8;
        }

        @keyframes fall {
            to {
                transform: translateY(100vh);
            }
        }

        h1,
        h2,
        h3,
        .santa-font {
            font-family: 'Mountains of Christmas', cursive;
        }

        .navbar {
            background: rgba(196, 30, 58, 0.95) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-family: 'Mountains of Christmas', cursive;
            font-size: 1.8rem;
            color: var(--christmas-gold) !important;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .container-main {
            margin-top: 30px;
            margin-bottom: 50px;
        }

        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            background: rgba(255, 255, 255, 0.98);
        }

        .btn-christmas {
            background: linear-gradient(135deg, var(--christmas-red), #8B0000);
            border: none;
            color: white;
            padding: 15px 40px;
            font-size: 1.2rem;
            font-weight: 600;
            border-radius: 50px;
            box-shadow: 0 5px 15px rgba(196, 30, 58, 0.4);
            transition: all 0.3s ease;
        }

        .btn-christmas:hover {
            transform: translateY(-3px);
            box-shadow: 0 7px 20px rgba(196, 30, 58, 0.6);
            background: linear-gradient(135deg, #8B0000, var(--christmas-red));
            color: white;
        }

        .btn-outline-christmas {
            border: 2px solid var(--christmas-green);
            color: var(--christmas-green);
            padding: 12px 30px;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        .btn-outline-christmas:hover {
            background: var(--christmas-green);
            color: white;
            transform: translateY(-2px);
        }

        .footer {
            background: rgba(22, 91, 51, 0.95);
            color: white;
            padding: 30px 0;
            margin-top: 50px;
        }

        .badge-trust {
            background: var(--christmas-gold);
            color: var(--christmas-green);
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 600;
        }

        .icon-christmas {
            color: var(--christmas-red);
            font-size: 3rem;
        }
    </style>



    @yield('extra-css')

 @production
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-JC6L940C7T"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'G-JC6L940C7T');
</script>
@endproduction

    @production
        <meta name="robots" content="index, follow">
    @else
        <meta name="robots" content="noindex, nofollow">
    @endproduction
</head>

<body>
    <!-- Snowflakes -->
    <div class="snowflakes" aria-hidden="true">
        @for ($i = 0; $i < 15; $i++)
            <div class="snowflake" style="left: {{ rand(0, 100) }}%; animation-duration: {{ rand(10, 30) }}s; animation-delay: {{ rand(0, 10) }}s;">❄</div>
        @endfor
    </div>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-gifts"></i> Letters2Santa
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('letter.create') }}">Send Letter</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container container-main">
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <!-- Footer -->
    <!-- Footer -->
    <footer class="footer">
        <div class="container text-center">
            <p class="mb-2">
                🔒 Secure Payments by Stripe | 🛡️ Privacy Protected | ❤️ Supporting Children's Charities
            </p>
            <p class="mb-3" style="font-size: 0.9rem; color: rgba(255,255,255,0.8);">
                © {{ date('Y') }} Letters2Santa. All rights reserved.
            </p>
            <p class="mb-0" style="font-size: 0.85rem;">
                <a href="{{ route('terms') }}" style="color: rgba(255,255,255,0.9); text-decoration: none; margin: 0 10px;">Terms & Conditions</a> |
                <a href="{{ route('privacy') }}" style="color: rgba(255,255,255,0.9); text-decoration: none; margin: 0 10px;">Privacy Policy</a> |
                <a href="{{ route('contact') }}" style="color: rgba(255,255,255,0.9); text-decoration: none; margin: 0 10px;">Contact Us</a>
            </p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    @yield('extra-js')
</body>

</html>
