@extends('layout')

@section('title', 'Letters2Santa - Magical Personalized Christmas Storybooks')

@section('content')
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
                    <a href="{{ route('letter.create') }}" class="btn btn-christmas btn-lg px-5 py-3 mb-3">
                        <i class="fas fa-envelope-open-text"></i> Create Your Letter
                    </a>
                    <p class="text-white mt-3 mb-0" style="font-size: 0.95rem; opacity: 0.9;">
                        <i class="fas fa-star" style="color: var(--gold);"></i>
                        <span style="text-decoration: line-through; opacity: 0.7;">$29.49</span>
                        <strong style="color: var(--gold); font-size: 1.2rem;">$9.49 AUD</strong> - Launch Price!
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
                            <h5 class="fw-bold mb-3">Fill the Form</h5>
                            <p class="text-muted mb-0">Enter your child's details and Christmas wishes</p>
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
                            <h5 class="fw-bold mb-3">Upload Photo</h5>
                            <p class="text-muted mb-0">Optional: Add a photo to create their cartoon character</p>
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
                            <h5 class="fw-bold mb-3">Secure Payment</h5>
                            <p class="text-muted mb-0">Launch price $9.49 AUD - Powered by Stripe</p>
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

    <!-- Pricing -->
    <div class="row mb-5">
        <div class="col-lg-6 mx-auto">
            <div class="card" style="background: linear-gradient(135deg, #fff8dc, #ffffff); border: 3px solid var(--christmas-red); box-shadow: 0 10px 30px rgba(0,0,0,0.2); border-radius: 20px;">
                <div class="card-body p-5 text-center">
                    <!-- Launch Badge -->
                    <div class="badge mb-3" style="background: linear-gradient(135deg, var(--christmas-red), #a01828); color: white; font-size: 1rem; padding: 10px 20px; border-radius: 50px;">
                        <i class="fas fa-rocket"></i> LAUNCH SPECIAL - 68% OFF!
                    </div>

                    <h3 class="santa-font mb-4" style="color: var(--christmas-red); font-size: 2rem;">
                        Limited Time Launch Pricing
                    </h3>

                    <!-- Pricing -->
                    <div class="mb-3">
                        <span class="text-muted" style="font-size: 1.5rem; text-decoration: line-through;">
                            $29.49 AUD
                        </span>
                    </div>
                    <div class="display-1 santa-font mb-2" style="color: var(--christmas-green);">
                        $9.49 AUD
                    </div>
                    <p class="text-muted mb-4">
                        <small style="font-size: 0.9rem;">
                            <i class="fas fa-globe"></i>
                            Approx: <strong>$6.07 USD</strong> · <strong>£4.83 GBP</strong> · <strong>€5.69 EUR</strong> · <strong>$8.70 CAD</strong>
                        </small>
                    </p>
                    <div class="alert alert-warning" style="background: rgba(255, 193, 7, 0.1); border: 1px solid rgba(255, 193, 7, 0.5);">
                        <small>
                            <i class="fas fa-clock"></i>
                            <strong>Launch price ends soon!</strong> Regular price $29.49 AUD
                        </small>
                    </div>
                    <div class="alert alert-info" style="background: rgba(23, 162, 184, 0.1); border: 1px solid rgba(23, 162, 184, 0.3);">
                        <small>
                            <i class="fas fa-credit-card"></i>
                            <strong>International customers:</strong> Final price will be converted to your local currency at checkout
                        </small>
                    </div>
                    <ul class="list-unstyled mb-4 text-start" style="max-width: 400px; margin: 0 auto;">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Personalized digital e-book</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Cartoon version of your child</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Printable certificate from Santa</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Delivered on Christmas Day</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> 100% secure payment</li>
                    </ul>
                    <a href="{{ route('letter.create') }}" class="btn btn-christmas btn-lg px-5 py-3">
                        <i class="fas fa-magic"></i> Get Launch Price Now
                    </a>
                    <p class="mt-3 mb-0 text-muted">
                        <small><i class="fas fa-shield-alt"></i> 68% savings - Lock in this price forever!</small>
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
                            <p class="mt-2 mb-0"><strong>Supporting Charity</strong></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
