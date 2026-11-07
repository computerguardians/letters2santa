@extends('layout')

@section('title', 'Letters2Santa - Magical Personalized Christmas Storybooks')

@section('content')
    <!-- Thank You Banner -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="thank-you-banner">
                <div class="ty-content">
                    <div class="ty-icon">🎅</div>
                    <h2 class="santa-font mb-2">Thank You for an Amazing Christmas!</h2>
                    @php
                        $now = new \Carbon\Carbon();
                        $christmas = \Carbon\Carbon::create(2026, 12, 25, 0, 0, 0);
                    @endphp

                    @if($now->lt($christmas))
                        <p class="lead mb-3">✨ All letters will be delivered on Christmas Day morning! ✨</p>
                        <p class="mb-0">Thank you to everyone who submitted their magical Christmas stories. Santa's elves are working hard preparing all the personalized e-books for delivery!</p>
                    @else
                        <p class="lead mb-3">🎄 Thousands of Magical Letters Delivered! 🎄</p>
                        <p class="mb-0">Thank you to all the families who made this Christmas special! We've successfully delivered thousands of personalized e-books and letters from Santa. We hope your children loved their magical stories!</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        .thank-you-banner {
            background: linear-gradient(135deg, var(--christmas-red), #c41e3a);
            border-radius: 20px;
            padding: 40px 20px;
            text-align: center;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            border: 3px solid var(--gold);
        }

        .thank-you-banner::before {
            content: '🎁';
            position: absolute;
            font-size: 200px;
            opacity: 0.05;
            top: -50px;
            right: -50px;
            animation: float 6s ease-in-out infinite;
        }

        .ty-content {
            position: relative;
            z-index: 1;
            color: white;
        }

        .ty-content h2 {
            color: var(--gold);
            font-size: 2.5rem;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }

        .ty-content .lead {
            font-size: 1.3rem;
            color: white;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
            font-weight: bold;
        }

        .ty-content p {
            font-size: 1.1rem;
            line-height: 1.6;
        }

        .ty-icon {
            font-size: 4rem;
            animation: bounce 2s infinite;
            margin-bottom: 20px;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        @media (max-width: 768px) {
            .ty-content h2 {
                font-size: 2rem;
            }
            .ty-content .lead {
                font-size: 1.1rem;
            }
            .ty-content p {
                font-size: 1rem;
            }
            .ty-icon {
                font-size: 3rem;
            }
        }
    </style>

    <!-- Hero Section -->
    <div class="row align-items-center mb-5">
        <div class="col-lg-6 mb-4 mb-lg-0">
            <div class="hero-content">
                <h1 class="santa-font display-3 mb-4" style="color: white; text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">
                    🎅 Magical Christmas Memories!
                </h1>
                <p class="lead text-white mb-4" style="font-size: 1.3rem; text-shadow: 1px 1px 2px rgba(0,0,0,0.3);">
                    Beautiful personalized digital storybooks featuring your child's name and cartoon character
                </p>
                @if($now->lt($christmas))
                    <div class="text-center text-md-start">
                        <div class="alert alert-success" style="background: rgba(255,255,255,0.9); border: 2px solid var(--christmas-green);">
                            <i class="fas fa-check-circle" style="color: var(--christmas-green);"></i>
                            <strong>Orders Closed for This Year</strong><br>
                            <small>All submissions are being prepared for Christmas Day delivery!</small>
                        </div>
                    </div>
                @else
                    <div class="text-center text-md-start">
                        <div class="alert alert-info" style="background: rgba(255,255,255,0.9); border: 2px solid var(--christmas-green);">
                            <i class="fas fa-heart" style="color: var(--christmas-red);"></i>
                            <strong>Thank you for a wonderful Christmas 2025!</strong><br>
                            <small>Join us again next year for more magical stories!</small>
                        </div>
                    </div>
                @endif
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
                        <strong>Personalized for each child!</strong>
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
                        🎁 What Each Child Received
                    </h2>
                    <div class="row g-4">
                        <div class="col-md-3 col-6 text-center">
                            <div class="feature-icon mb-3">
                                <i class="fas fa-book-open" style="font-size: 3rem; color: var(--christmas-green);"></i>
                            </div>
                            <h5 class="fw-bold">Personalized E-Book</h5>
                            <p class="text-muted mb-0">Digital storybook with their name</p>
                        </div>
                        <div class="col-md-3 col-6 text-center">
                            <div class="feature-icon mb-3">
                                <i class="fas fa-child" style="font-size: 3rem; color: var(--christmas-red);"></i>
                            </div>
                            <h5 class="fw-bold">Cartoon Character</h5>
                            <p class="text-muted mb-0">Their photo transformed into story art!</p>
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
                            <h5 class="fw-bold">Christmas Delivery</h5>
                            <p class="text-muted mb-0">Delivered on Christmas Day morning</p>
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
                    <h3 class="santa-font mb-3" style="font-size: 2rem;">Check Your Order</h3>
                    <p class="lead mb-4">Track your order status and access your magical storybook!</p>
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

    <!-- Stats Section (Only show after Christmas) -->
    @if($now->gte($christmas))
    <div class="row mb-5" >
        <div class="col-12">
            <div class="card" style=" border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.2); border-radius: 20px;">
                <div class="card-body p-5 text-center">
                    <h2 class="santa-font mb-4" style="color: var(--christmas-red); font-size: 2.5rem;">
                        🎄 Christmas 2025 Impact 🎄
                    </h2>
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="stat-box">
                                <div class="stat-number" style="font-size: 3rem; font-weight: bold; color: var(--christmas-red);">1000+</div>
                                <p class="stat-label" style="font-size: 1.2rem; color: #333;">Letters Delivered</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-box">
                                <div class="stat-number" style="font-size: 3rem; font-weight: bold; color: var(--christmas-green);">500+</div>
                                <p class="stat-label" style="font-size: 1.2rem; color: #333;">Happy Families</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-box">
                                <div class="stat-number" style="font-size: 3rem; font-weight: bold; color: var(--christmas-red);">100%</div>
                                <p class="stat-label" style="font-size: 1.2rem; color: #333;">Christmas Magic</p>
                            </div>
                        </div>
                    </div>
                    <p class="mt-4 mb-0" style="font-size: 1.1rem; color: #333;">
                        <strong>Thank you for making this Christmas unforgettable! See you next year! 🎅</strong>
                    </p>
                </div>
            </div>
        </div>
    </div>
    @endif

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
