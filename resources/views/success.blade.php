@extends('layout')

@section('title', 'Thank You! - Letters2Santa')

@section('content')
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-body p-5 text-center">
                    <!-- Success Icon -->
                    <div class="mb-4">
                        <i class="fas fa-check-circle" style="font-size: 5rem; color: var(--christmas-green);"></i>
                    </div>

                    <!-- Heading -->
                    <h1 class="santa-font mb-4" style="color: var(--christmas-red); font-size: 3rem;">
                        <i class="fas fa-gifts"></i> Ho Ho Ho!
                    </h1>

                    <h2 class="h3 mb-4" style="color: var(--christmas-green);">
                        Santa Has Received {{ $letter->child_name }}'s Letter!
                    </h2>

                    <!-- Confirmation Message - Different for Free vs Premium -->
                    @if($letter->tier === 'free')
                        <div class="alert alert-success mb-4">
                            <i class="fas fa-envelope-open-text"></i>
                            <strong>Order Confirmed!</strong> Your FREE e-book has been registered.
                        </div>
                    @else
                        <div class="alert alert-success mb-4">
                            <i class="fas fa-envelope-open-text"></i>
                            <strong>Payment Successful!</strong> Your order has been confirmed.
                        </div>
                    @endif

                    <!-- Order Details -->
                    <div class="card mb-4" style="background: linear-gradient(135deg, #fff8dc, #ffffff);">
                        <div class="card-body p-4">
                            <h4 class="santa-font mb-3" style="color: var(--christmas-red);">
                                <i class="fas fa-clipboard-list"></i> Order Details
                            </h4>
                            <div class="order-details">
                                <div class="detail-row">
                                    <span class="detail-label">Order ID:</span>
                                    <span class="detail-value"><code>{{ $letter->order_id }}</code></span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Package:</span>
                                    <span class="detail-value">
                                        @if($letter->tier === 'free')
                                            <span class="badge" style="background: var(--christmas-green); color: white; padding: 5px 15px; border-radius: 20px;">✨ FREE Basic</span>
                                        @else
                                            <span class="badge" style="background: var(--christmas-red); color: white; padding: 5px 15px; border-radius: 20px;">⭐ Premium</span>
                                        @endif
                                    </span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Child's Name:</span>
                                    <span class="detail-value">{{ $letter->child_name }}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Age Range:</span>
                                    <span class="detail-value">{{ $letter->age_range }} years</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Parent Mobile:</span>
                                    <span class="detail-value">{{ $letter->parent_mobile }}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Confirmation Email:</span>
                                    <span class="detail-value">{{ $letter->parent_email }}</span>
                                </div>
                                @if($letter->tier === 'premium')
                                <div class="detail-row">
                                    <span class="detail-label">Amount Paid:</span>
                                    <span class="detail-value"><strong style="color: var(--christmas-red);">$4.99 AUD</strong></span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <style>
                        .order-details {
                            display: flex;
                            flex-direction: column;
                            gap: 15px;
                        }

                        .detail-row {
                            display: flex;
                            justify-content: space-between;
                            align-items: center;
                            padding: 12px;
                            background: rgba(255, 255, 255, 0.5);
                            border-radius: 8px;
                            gap: 10px;
                        }

                        .detail-label {
                            font-weight: 600;
                            color: #333;
                            min-width: 140px;
                        }

                        .detail-value {
                            text-align: right;
                            color: #666;
                            word-break: break-word;
                        }

                        /* Mobile responsive */
                        @media (max-width: 576px) {
                            .detail-row {
                                flex-direction: column;
                                align-items: flex-start;
                                gap: 5px;
                            }

                            .detail-label {
                                min-width: auto;
                                font-size: 0.9rem;
                            }

                            .detail-value {
                                text-align: left;
                                font-size: 0.95rem;
                                width: 100%;
                            }

                            .detail-value code {
                                font-size: 0.85rem;
                                word-break: break-all;
                            }
                        }
                    </style>

                    <!-- What Happens Next -->
                    <div class="mb-4">
                        <h3 class="santa-font mb-4" style="color: var(--christmas-green);">
                            <i class="fas fa-magic"></i> What Happens Next?
                        </h3>

                        <div class="row text-start">
                            <div class="col-md-6 mb-3">
                                <div class="p-3 h-100" style="background: #f8f9fa; border-radius: 15px;">
                                    <i class="fas fa-envelope-open-text" style="color: var(--christmas-red); font-size: 2rem;"></i>
                                    <h5 class="mt-3">1. Santa's Workshop</h5>
                                    <p class="mb-0">Santa's elves are busy creating {{ $letter->child_name }}'s personalised magical storybook right now!</p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="p-3 h-100" style="background: #f8f9fa; border-radius: 15px;">
                                    <i class="fas fa-calendar-check" style="color: var(--christmas-red); font-size: 2rem;"></i>
                                    <h5 class="mt-3">2. Christmas Day Delivery</h5>
                                    <p class="mb-0">On Christmas Day, {{ $letter->child_name }} will receive a special email with their magical surprise!</p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="p-3 h-100" style="background: #f8f9fa; border-radius: 15px;">
                                    <i class="fas fa-book-open" style="color: var(--christmas-red); font-size: 2rem;"></i>
                                    <h5 class="mt-3">3. Personalised E-Book</h5>
                                    <p class="mb-0">A beautiful digital storybook featuring {{ $letter->child_name }}'s name throughout the adventure!</p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="p-3 h-100" style="background: #f8f9fa; border-radius: 15px;">
                                    @if($letter->tier === 'premium')
                                        <i class="fas fa-camera" style="color: var(--christmas-red); font-size: 2rem;"></i>
                                        <h5 class="mt-3">4. Premium Features</h5>
                                        <p class="mb-0">Including {{ $letter->child_name }}'s cartoon character, certificate, and letter from Santa!</p>
                                    @else
                                        <i class="fas fa-certificate" style="color: var(--christmas-red); font-size: 2rem;"></i>
                                        <h5 class="mt-3">4. Special Letter</h5>
                                        <p class="mb-0">Plus a personal letter from Santa himself!</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Email Confirmation Note -->
                    <div class="alert alert-info mb-4">
                        <i class="fas fa-inbox"></i>
                        <strong>Check Your Email:</strong> We've sent a confirmation to <strong>{{ $letter->parent_email }}</strong>
                        <br>
                        <small>(Please check your spam folder if you don't see it within a few minutes)</small>
                    </div>

                    <!-- Upsell to Premium (Only for FREE tier users) -->
                    @if($letter->tier === 'free')
                    <div class="card mb-4" style="background: linear-gradient(135deg, var(--christmas-red), #8B0000); border: 3px solid #FFD700;">
                        <div class="card-body p-4">
                            <div class="badge mb-3" style="background: #FFD700; color: #000; padding: 8px 20px; border-radius: 20px; font-weight: bold;">
                                ⚡ BLACK FRIDAY SPECIAL ⚡
                            </div>
                            <h4 class="santa-font text-white mb-3">
                                <i class="fas fa-star"></i> Want to See {{ $letter->child_name }} in the Story?
                            </h4>
                            <p class="text-white mb-3" style="font-size: 1.1rem;">
                                <strong>Upgrade to Premium for only $4.99!</strong><br>
                                Add a cartoon version of {{ $letter->child_name }} to appear in the storybook!
                            </p>
                            <ul class="text-white text-start mb-3" style="list-style: none; padding-left: 0;">
                                <li class="mb-2"><i class="fas fa-check-circle" style="color: #FFD700;"></i> Everything in your FREE package</li>
                                <li class="mb-2"><i class="fas fa-check-circle" style="color: #FFD700;"></i> <strong>Cartoon photo of {{ $letter->child_name }}</strong> in the story!</li>
                                <li class="mb-2"><i class="fas fa-check-circle" style="color: #FFD700;"></i> Premium printable certificate</li>
                                <li class="mb-2"><i class="fas fa-check-circle" style="color: #FFD700;"></i> High-resolution PDF quality</li>
                            </ul>
                            <a href="{{ route('letter.create', ['tier' => 'premium']) }}" class="btn btn-lg" style="background: #FFD700; color: #000; font-weight: 700; padding: 15px 40px; border-radius: 50px; border: none;">
                                <i class="fas fa-arrow-up"></i> UPGRADE TO PREMIUM - $4.99
                            </a>
                            <p class="text-white mt-3 mb-0">
                                <small><i class="fas fa-clock"></i> Black Friday price ends Dec 2nd!</small>
                            </p>
                        </div>
                    </div>
                    @endif

                    <!-- Printed Book Upsell (For Premium users) -->
                    @if($letter->tier === 'premium')
                    <div class="card mb-4" style="background: linear-gradient(135deg, var(--christmas-green), #0d472a);">
                        <div class="card-body p-4">
                            <h4 class="santa-font text-white mb-3">
                                <i class="fas fa-book"></i> Want a Physical Keepsake?
                            </h4>
                            <p class="text-white mb-3">
                                Turn your digital storybook into a beautiful printed hardcover book!
                            </p>
                            <button class="btn btn-lg" style="background: white; color: var(--christmas-green); font-weight: 600; padding: 12px 30px; border-radius: 50px;" disabled>
                                <i class="fas fa-book"></i> Printed Book - Coming Soon!
                            </button>
                            <p class="text-white mt-2 mb-0">
                                <small>Premium hardcover book delivered to your door</small>
                            </p>
                        </div>
                    </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="d-grid gap-2 mb-3">
                        <a href="{{ route('home') }}" class="btn btn-outline-christmas btn-lg">
                            <i class="fas fa-home"></i> Back to Home
                        </a>
                    </div>

                    <div class="text-muted">
                        <small>
                            <i class="fas fa-question-circle"></i>
                            Questions? Email us at: <strong>workshop@letters2santa.com</strong>
                        </small>
                    </div>
                </div>
            </div>

            <!-- Share Section -->
            <div class="card mt-4" style="background: linear-gradient(135deg, #e8f5e9, #ffffff);">
                <div class="card-body p-4 text-center">
                    <h4 class="santa-font mb-3" style="color: var(--christmas-green);">
                        <i class="fas fa-share-alt"></i> Spread the Christmas Magic!
                    </h4>
                    <p class="mb-3">
                        Know other families who'd love this magical experience? Share Letters2Santa with them!
                    </p>

                    <!-- Share Buttons -->
                    <div class="d-flex gap-2 justify-content-center flex-wrap mb-3">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('black-friday')) }}"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="btn btn-outline-christmas">
                            <i class="fab fa-facebook"></i> Share on Facebook
                        </a>
                        <a href="https://wa.me/?text={{ urlencode('🎅 Check out Letters2Santa - FREE personalized Christmas storybooks + Premium for only $4.99! Black Friday Special: ' . route('black-friday')) }}"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="btn btn-outline-christmas">
                            <i class="fab fa-whatsapp"></i> Share on WhatsApp
                        </a>
                    </div>

                    <!-- Social Media Links -->
                    <p class="mb-2 text-muted" style="font-size: 0.9rem;">
                        <strong>Follow us for updates & exclusive offers:</strong>
                    </p>
                    <div class="d-flex gap-3 justify-content-center">
                        <a href="https://www.facebook.com/letters2santaofficial"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="text-decoration-none"
                           style="color: var(--christmas-green); font-size: 1.5rem;">
                            <i class="fab fa-facebook-square"></i>
                        </a>
                        <a href="https://www.instagram.com/letters2santaofficial"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="text-decoration-none"
                           style="color: var(--christmas-green); font-size: 1.5rem;">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                    <p class="mb-0 mt-2" style="font-size: 0.85rem; color: #666;">
                        @letters2santaofficial
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extra-js')
    <script>
        // Optional: Add confetti or snow animation on success page
        console.log('Order {{ $letter->order_id }} confirmed! Tier: {{ $letter->tier }}');

        @if($letter->tier === 'free')
        console.log('FREE tier - no payment processed');
        @else
        console.log('PREMIUM tier - payment successful');
        @endif
    </script>
@endsection
