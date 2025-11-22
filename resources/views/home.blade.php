@extends('layout')

@section('title', 'Letters2Santa - Magical Personalized Christmas Storybooks')

@section('content')
    <!-- BLACK FRIDAY BANNER -->
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('black-friday') }}" style="text-decoration: none;">
                <div class="black-friday-banner">
                    <div class="bf-content">
                        <div class="bf-flash">⚡</div>
                        <h2 class="santa-font mb-2">BLACK FRIDAY SPECIAL</h2>
                        <p class="lead mb-3">🎁 FREE E-Books + Premium for Only $4.99!</p>
                        <div class="bf-timer" id="bfCountdown">
                            <span class="time-unit">
                                <span class="number" id="bfDays">00</span>
                                <span class="label">Days</span>
                            </span>
                            <span class="separator">:</span>
                            <span class="time-unit">
                                <span class="number" id="bfHours">00</span>
                                <span class="label">Hours</span>
                            </span>
                            <span class="separator">:</span>
                            <span class="time-unit">
                                <span class="number" id="bfMinutes">00</span>
                                <span class="label">Minutes</span>
                            </span>
                            <span class="separator">:</span>
                            <span class="time-unit">
                                <span class="number" id="bfSeconds">00</span>
                                <span class="label">Seconds</span>
                            </span>
                        </div>
                        <div class="btn btn-warning btn-lg mt-3" style="font-weight: bold; animation: pulse 2s infinite;">
                            🎄 VIEW BLACK FRIDAY DEALS 🎄
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <style>
        .black-friday-banner {
            background: linear-gradient(135deg, #000000, #1a1a1a);
            border-radius: 20px;
            padding: 40px 20px;
            text-align: center;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,0.5);
            transition: transform 0.3s ease;
            border: 3px solid #ffd700;
        }

        .black-friday-banner:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 50px rgba(0,0,0,0.6);
        }

        .black-friday-banner::before {
            content: '🎁';
            position: absolute;
            font-size: 200px;
            opacity: 0.05;
            top: -50px;
            right: -50px;
            animation: float 6s ease-in-out infinite;
        }

        .bf-content {
            position: relative;
            z-index: 1;
            color: white;
        }

        .bf-content h2 {
            color: #ffd700;
            font-size: 2.5rem;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }

        .bf-content .lead {
            font-size: 1.3rem;
            color: white;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
        }

        .bf-flash {
            font-size: 3rem;
            animation: flash 1.5s infinite;
        }

        .bf-timer {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin: 20px 0;
            flex-wrap: wrap;
        }

        .time-unit {
            display: inline-flex;
            flex-direction: column;
            align-items: center;
            background: rgba(255, 255, 255, 0.1);
            padding: 15px 20px;
            border-radius: 10px;
            min-width: 80px;
        }

        .time-unit .number {
            font-size: 2rem;
            font-weight: bold;
            color: #ffd700;
            display: block;
            line-height: 1;
        }

        .time-unit .label {
            font-size: 0.8rem;
            color: white;
            margin-top: 5px;
        }

        .separator {
            font-size: 2rem;
            font-weight: bold;
            color: #ffd700;
            align-self: center;
        }

        @keyframes flash {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }

        @media (max-width: 768px) {
            .bf-content h2 {
                font-size: 2rem;
            }
            .bf-content .lead {
                font-size: 1.1rem;
            }
            .time-unit {
                min-width: 70px;
                padding: 10px 15px;
            }
            .time-unit .number {
                font-size: 1.5rem;
            }
            .separator {
                font-size: 1.5rem;
            }
        }
    </style>

    <!-- Hero Section -->
    <div class="row align-items-center mb-5">
        <div class="col-lg-6 mb-4 mb-lg-0">
            <div class="hero-content">
                <h1 class="santa-font display-3 mb-4" style="color: white; text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">
                    🎅 Make Christmas Magical!
                </h1>
                <p class="lead text-white mb-4" style="font-size: 1.3rem; text-shadow: 1px 1px 2px rgba(0,0,0,0.3);">
                    A beautiful digital storybook featuring your child's name and a cartoon version of your child in the story
                </p>
                <div class="text-center text-md-start">
                    <a href="{{ route('black-friday') }}" class="btn btn-christmas btn-lg px-5 py-3 mb-3">
                        <i class="fas fa-gift"></i> See Black Friday Deals
                    </a>
                    <p class="text-white mt-3 mb-0" style="font-size: 0.95rem; opacity: 0.9;">
                        <i class="fas fa-star" style="color: var(--gold);"></i>
                        <strong style="color: var(--gold); font-size: 1.2rem;">FREE E-Books Available!</strong>
                    </p>
                </div>
            </div>
        </div>

        <style>
            .hero-content {
                display: flex;
                flex-direction: column;
                justify-content: center;
                height: 100%;
            }

            /* Center everything on mobile, left-align on desktop */
            @media (max-width: 991px) {
                .hero-content {
                    text-align: center;
                }

                .hero-content h1,
                .hero-content p {
                    text-align: center;
                }
            }

            @media (min-width: 992px) {
                .hero-content {
                    padding-right: 40px;
                }
            }
        </style>
        <div class="col-lg-6">
            <div style="max-width: 60%; margin: 0 auto;">
                <!-- Book Card -->
                <div class="book-card">
                    <div class="book-border">
                        <img src="{{ asset('assets/images/cartoon_boy_on_santa_lap.png') }}" alt="Child with Santa - Storybook Preview" class="img-fluid" style="border-radius: 8px; width: 100%;">
                    </div>
                </div>

                <!-- Caption -->
                <div class="text-center mt-3">
                    <p class="mb-0" style="color: white; font-size: 1.1rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">
                        <i class="fas fa-magic" style="color: var(--gold);"></i>
                        <strong>See your child in the story!</strong>
                    </p>
                </div>
            </div>
        </div>

        <style>
            .book-card {
                background: linear-gradient(135deg, #8B4513, #A0522D);
                padding: 20px;
                border-radius: 12px;
                box-shadow:
                    0 10px 30px rgba(0, 0, 0, 0.4),
                    inset -5px 0 15px rgba(0, 0, 0, 0.3),
                    inset 5px 0 15px rgba(255, 255, 255, 0.1);
                border-left: 8px solid #6B3410;
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }

            .book-card:hover {
                transform: translateY(-5px) scale(1.02);
                box-shadow:
                    0 15px 40px rgba(0, 0, 0, 0.5),
                    inset -5px 0 15px rgba(0, 0, 0, 0.3),
                    inset 5px 0 15px rgba(255, 255, 255, 0.1);
            }

            .book-border {
                background: white;
                padding: 10px;
                border-radius: 8px;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            }

            @media (max-width: 768px) {
                .book-card {
                    padding: 15px;
                }
            }
        </style>
    </div>

    <!-- What You Get Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card" style="background: linear-gradient(135deg, #ffffff, #fff8dc); border: none; box-shadow: 0 5px 20px rgba(0,0,0,0.1); border-radius: 20px;">
                <div class="card-body p-5">
                    <h2 class="santa-font text-center mb-5" style="color: var(--christmas-red); font-size: 2.5rem;">
                        🎁 What Your Child Gets
                    </h2>
                    <div class="row g-4">
                        <div class="col-md-3 col-6 text-center">
                            <div class="feature-icon mb-3">
                                <i class="fas fa-book-open" style="font-size: 3rem; color: var(--christmas-green);"></i>
                            </div>
                            <h5 class="fw-bold">Personalized E-Book</h5>
                            <p class="text-muted mb-0">Digital storybook with your child's name</p>
                        </div>
                        <div class="col-md-3 col-6 text-center">
                            <div class="feature-icon mb-3">
                                <i class="fas fa-child" style="font-size: 3rem; color: var(--christmas-red);"></i>
                            </div>
                            <h5 class="fw-bold">Cartoon Character</h5>
                            <p class="text-muted mb-0">Upload photo - see them in the story!</p>
                        </div>
                        <div class="col-md-3 col-6 text-center">
                            <div class="feature-icon mb-3">
                                <i class="fas fa-certificate" style="font-size: 3rem; color: var(--christmas-green);"></i>
                            </div>
                            <h5 class="fw-bold">Certificate</h5>
                            <p class="text-muted mb-0">Printable certificate from Santa</p>
                        </div>
                        <div class="col-md-3 col-6 text-center">
                            <div class="feature-icon mb-3">
                                <i class="fas fa-envelope" style="font-size: 3rem; color: var(--christmas-red);"></i>
                            </div>
                            <h5 class="fw-bold">Email Delivery</h5>
                            <p class="text-muted mb-0">Sent to your inbox on Christmas Day</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- How It Works -->
    <div class="row mb-5">
        <div class="col-12">
            <h2 class="santa-font text-center mb-5" style="color: white; font-size: 2.5rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">
                ✨ How It Works
            </h2>
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="card h-100 text-center" style="border: none; box-shadow: 0 5px 15px rgba(0,0,0,0.1); border-radius: 15px;">
                        <div class="card-body p-4">
                            <div class="step-number mb-3"
                                style="width: 60px; height: 60px; background: var(--christmas-red); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; font-size: 1.5rem; font-weight: bold;">
                                1
                            </div>
                            <h5 class="fw-bold mb-3">Choose Your Tier</h5>
                            <p class="text-muted mb-0">Select FREE basic or Premium package</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card h-100 text-center" style="border: none; box-shadow: 0 5px 15px rgba(0,0,0,0.1); border-radius: 15px;">
                        <div class="card-body p-4">
                            <div class="step-number mb-3"
                                style="width: 60px; height: 60px; background: var(--christmas-green); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; font-size: 1.5rem; font-weight: bold;">
                                2
                            </div>
                            <h5 class="fw-bold mb-3">Fill the Form</h5>
                            <p class="text-muted mb-0">Enter your child's details and Christmas wishes</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card h-100 text-center" style="border: none; box-shadow: 0 5px 15px rgba(0,0,0,0.1); border-radius: 15px;">
                        <div class="card-body p-4">
                            <div class="step-number mb-3"
                                style="width: 60px; height: 60px; background: var(--christmas-red); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; font-size: 1.5rem; font-weight: bold;">
                                3
                            </div>
                            <h5 class="fw-bold mb-3">Upload Photo</h5>
                            <p class="text-muted mb-0">Premium: Add photo for cartoon character</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card h-100 text-center" style="border: none; box-shadow: 0 5px 15px rgba(0,0,0,0.1); border-radius: 15px;">
                        <div class="card-body p-4">
                            <div class="step-number mb-3"
                                style="width: 60px; height: 60px; background: var(--christmas-green); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; font-size: 1.5rem; font-weight: bold;">
                                4
                            </div>
                            <h5 class="fw-bold mb-3">Christmas Magic</h5>
                            <p class="text-muted mb-0">Delivered to your email on Christmas Day!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Track Order CTA -->
    <div class="row mb-5">
        <div class="col-lg-8 mx-auto">
            <div class="card" style="background: linear-gradient(135deg, var(--christmas-green), #0d472a); border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.3); border-radius: 20px;">
                <div class="card-body p-5 text-center text-white">
                    <i class="fas fa-box-open" style="font-size: 3.5rem; margin-bottom: 20px;"></i>
                    <h3 class="santa-font mb-3" style="font-size: 2rem;">Already Ordered?</h3>
                    <p class="lead mb-4">Track your order status and see when your magical storybook will arrive!</p>
                    <a href="{{ route('order.check') }}" class="btn btn-light btn-lg px-5 py-3">
                        <i class="fas fa-search"></i> Track My Order
                    </a>
                    <p class="mt-3 mb-0" style="opacity: 0.9;">
                        <small><i class="fas fa-info-circle"></i> Enter your Order ID and email to check status</small>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Black Friday Pricing Teaser -->
    <div class="row mb-5">
        <div class="col-lg-10 mx-auto">
            <div class="card" style="background: linear-gradient(135deg, #000000, #1a1a1a); border: 3px solid #ffd700; box-shadow: 0 10px 30px rgba(0,0,0,0.4); border-radius: 20px;">
                <div class="card-body p-5 text-center text-white">
                    <div class="badge mb-3" style="background: linear-gradient(135deg, #ffd700, #ffed4e); color: #000; font-size: 1rem; padding: 10px 20px; border-radius: 50px; font-weight: bold;">
                        ⚡ BLACK FRIDAY EXCLUSIVE ⚡
                    </div>

                    <h3 class="santa-font mb-4" style="color: #ffd700; font-size: 2.5rem;">
                        Choose Your Perfect Package
                    </h3>

                    <div class="row g-4">
                        <!-- FREE Tier -->
                        <div class="col-md-6">
                            <div class="card h-100" style="background: rgba(255,255,255,0.1); border: 2px solid rgba(255,255,255,0.3); border-radius: 15px;">
                                <div class="card-body p-4">
                                    <h4 class="mb-3" style="color: #4ade80;">✨ FREE Basic</h4>
                                    <div class="display-4 mb-3" style="color: #4ade80; font-weight: bold;">FREE</div>
                                    <p class="mb-3" style="color: rgba(255,255,255,0.9);">
                                        <small><s>$29.49</s> - Save 100%!</small>
                                    </p>
                                    <ul class="list-unstyled text-start mb-4" style="color: rgba(255,255,255,0.9);">
                                        <li class="mb-2">✅ Personalized E-Book</li>
                                        <li class="mb-2">✅ Letter from Santa</li>
                                        <li class="mb-2">✅ Christmas Day Delivery</li>
                                        <li class="mb-2" style="opacity: 0.5;">❌ No Photo Character</li>
                                        <li class="mb-2" style="opacity: 0.5;">❌ No Certificate</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- PREMIUM Tier -->
                        <div class="col-md-6">
                            <div class="card h-100" style="background: linear-gradient(135deg, #ef4444, #dc2626); border: 3px solid #ffd700; border-radius: 15px; box-shadow: 0 5px 20px rgba(255,215,0,0.3);">
                                <div class="badge" style="background: #ffd700; color: #000; position: absolute; top: -12px; left: 50%; transform: translateX(-50%); padding: 5px 20px; border-radius: 20px; font-weight: bold; font-size: 0.8rem;">
                                    MOST POPULAR
                                </div>
                                <div class="card-body p-4 pt-5">
                                    <h4 class="mb-3" style="color: #ffd700;">⭐ Premium</h4>
                                    <div class="display-4 mb-1" style="color: #ffd700; font-weight: bold;">$4.99</div>
                                    <p class="mb-3" style="color: rgba(255,255,255,0.9);">
                                        <small><s>$9.49</s> - Save 48%!</small>
                                    </p>
                                    <ul class="list-unstyled text-start mb-4">
                                        <li class="mb-2">✅ Everything in FREE</li>
                                        <li class="mb-2"><strong style="color: #ffd700;">✅ YOUR Child's Cartoon Photo!</strong></li>
                                        <li class="mb-2">✅ Premium Certificate</li>
                                        <li class="mb-2">✅ High-Res PDF</li>
                                        <li class="mb-2">✅ Priority Support</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('black-friday') }}" class="btn btn-warning btn-lg px-5 py-3" style="font-weight: bold; font-size: 1.2rem;">
                            🎄 VIEW ALL BLACK FRIDAY DEALS 🎄
                        </a>
                    </div>

                    <p class="mt-3 mb-0" style="opacity: 0.8;">
                        <small><i class="fas fa-clock"></i> Limited Time: Nov 29 - Dec 2, 2025</small>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Trust & Safety -->
    <div class="row">
        <div class="col-12">
            <div class="card" style="background: rgba(255,255,255,0.95); border: none; box-shadow: 0 5px 15px rgba(0,0,0,0.1); border-radius: 15px;">
                <div class="card-body p-4">
                    <div class="row align-items-center text-center">
                        <div class="col-md-3 mb-3 mb-md-0">
                            <i class="fas fa-lock" style="font-size: 2.5rem; color: var(--christmas-green);"></i>
                            <p class="mt-2 mb-0"><strong>Secure Payment</strong></p>
                        </div>
                        <div class="col-md-3 mb-3 mb-md-0">
                            <i class="fas fa-shield-alt" style="font-size: 2.5rem; color: var(--christmas-green);"></i>
                            <p class="mt-2 mb-0"><strong>Privacy Protected</strong></p>
                        </div>
                        <div class="col-md-3 mb-3 mb-md-0">
                            <i class="fas fa-trash-alt" style="font-size: 2.5rem; color: var(--christmas-green);"></i>
                            <p class="mt-2 mb-0"><strong>Photos Auto-Deleted</strong></p>
                        </div>
                        <div class="col-md-3">
                            <i class="fas fa-heart" style="font-size: 2.5rem; color: var(--christmas-red);"></i>
                            <p class="mt-2 mb-0"><strong>Christmas Magic</strong></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extra-js')
<script>
// Black Friday countdown timer
const blackFridayEnd = new Date('2025-12-02T23:59:59').getTime();

function updateBFCountdown() {
    const now = new Date().getTime();
    const distance = blackFridayEnd - now;

    if (distance < 0) {
        document.getElementById('bfCountdown').innerHTML = '<p class="mb-0">Sale Ended</p>';
        return;
    }

    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

    document.getElementById('bfDays').textContent = String(days).padStart(2, '0');
    document.getElementById('bfHours').textContent = String(hours).padStart(2, '0');
    document.getElementById('bfMinutes').textContent = String(minutes).padStart(2, '0');
    document.getElementById('bfSeconds').textContent = String(seconds).padStart(2, '0');
}

// Update countdown every second
updateBFCountdown();
setInterval(updateBFCountdown, 1000);
</script>
@endsection
