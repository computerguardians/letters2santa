@extends('layout')

@section('title', 'Black Friday Special - Letters2Santa')

@section('extra-css')
<style>
    .bf-banner {
        background: linear-gradient(135deg, #000000, #1a1a1a);
        color: white;
        padding: 30px 20px;
        text-align: center;
        margin-bottom: 40px;
        border-radius: 15px;
        position: relative;
        overflow: hidden;
    }

    .bf-banner::before {
        content: '🎁';
        position: absolute;
        font-size: 150px;
        opacity: 0.1;
        top: -30px;
        right: -30px;
    }

    .bf-badge {
        background: linear-gradient(135deg, #ff6b6b, #ee5a6f);
        color: white;
        padding: 8px 20px;
        border-radius: 50px;
        font-weight: bold;
        font-size: 0.9rem;
        display: inline-block;
        margin-bottom: 15px;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    .countdown {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin: 20px 0;
    }

    .countdown-item {
        background: rgba(255, 255, 255, 0.1);
        padding: 15px 20px;
        border-radius: 10px;
        min-width: 80px;
    }

    .countdown-number {
        font-size: 2rem;
        font-weight: bold;
        display: block;
        color: #ffd700;
    }

    .countdown-label {
        font-size: 0.8rem;
        opacity: 0.9;
    }

    .pricing-card {
        border: 2px solid #e5e7eb;
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 20px;
        background: white;
        transition: all 0.3s;
        height: 100%;
    }

    .pricing-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }

    .pricing-card.premium {
        border: 3px solid var(--christmas-red);
        position: relative;
        background: linear-gradient(135deg, #fff8dc, #ffffff);
    }

    .pricing-card.premium::before {
        content: 'MOST POPULAR';
        position: absolute;
        top: -15px;
        left: 50%;
        transform: translateX(-50%);
        background: var(--christmas-red);
        color: white;
        padding: 8px 25px;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: bold;
    }

    .pricing-header {
        text-align: center;
        margin-bottom: 25px;
    }

    .pricing-title {
        font-size: 1.8rem;
        font-weight: bold;
        color: var(--christmas-red);
        margin-bottom: 15px;
    }

    .pricing-price {
        font-size: 3rem;
        font-weight: bold;
        color: var(--christmas-green);
    }

    .pricing-price small {
        font-size: 1.2rem;
        text-decoration: line-through;
        color: #999;
        margin-right: 10px;
    }

    .savings-badge {
        background: #10b981;
        color: white;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
        margin-top: 10px;
        display: inline-block;
    }

    .features-list {
        list-style: none;
        padding: 0;
        margin: 25px 0;
    }

    .features-list li {
        padding: 12px 0;
        border-bottom: 1px solid #f0f0f0;
        font-size: 1rem;
    }

    .features-list li:last-child {
        border-bottom: none;
    }

    .features-list .icon {
        margin-right: 10px;
        font-size: 1.2rem;
    }

    .features-list .included {
        color: #10b981;
    }

    .features-list .excluded {
        color: #ef4444;
    }

    .cta-button {
        width: 100%;
        padding: 18px;
        font-size: 1.2rem;
        font-weight: bold;
        border-radius: 50px;
        border: none;
        transition: all 0.3s;
    }

    .btn-free {
        background: linear-gradient(135deg, var(--christmas-green), #0d472a);
        color: white;
    }

    .btn-free:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(22, 91, 51, 0.4);
    }

    .btn-premium {
        background: linear-gradient(135deg, var(--christmas-red), #a01828);
        color: white;
    }

    .btn-premium:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(196, 30, 58, 0.4);
    }
</style>
@endsection

@section('content')
<!-- Black Friday Banner -->
<div class="bf-banner">
    <div class="bf-badge">
        ⚡ BLACK FRIDAY EXCLUSIVE ⚡
    </div>
    <h1 class="santa-font mb-3" style="font-size: 3rem;">
        🎄 Magical Christmas Special 🎄
    </h1>
    <p class="lead mb-4">
        FREE Personalized E-Books for Every Child!<br>
        Limited Time: November 29 - December 2, 2024
    </p>

    <!-- Countdown Timer -->
    <div class="countdown" id="countdown">
        <div class="countdown-item">
            <span class="countdown-number" id="days">00</span>
            <span class="countdown-label">Days</span>
        </div>
        <div class="countdown-item">
            <span class="countdown-number" id="hours">00</span>
            <span class="countdown-label">Hours</span>
        </div>
        <div class="countdown-item">
            <span class="countdown-number" id="minutes">00</span>
            <span class="countdown-label">Minutes</span>
        </div>
        <div class="countdown-item">
            <span class="countdown-number" id="seconds">00</span>
            <span class="countdown-label">Seconds</span>
        </div>
    </div>
</div>

<!-- Pricing Tiers -->
<div class="row mb-5">
    <!-- FREE Tier -->
    <div class="col-lg-6 mb-4">
        <div class="pricing-card">
            <div class="pricing-header">
                <h2 class="pricing-title">
                    ✨ Free Basic
                </h2>
                <div class="pricing-price">
                    <small>$29.49</small> FREE
                </div>
                <div class="savings-badge">
                    Save 100% - $29.49 OFF!
                </div>
            </div>

            <ul class="features-list">
                <li>
                    <span class="icon included">✅</span>
                    <strong>Personalized with child's name</strong>
                </li>
                <li>
                    <span class="icon included">✅</span>
                    Age-appropriate Christmas story
                </li>
                <li>
                    <span class="icon included">✅</span>
                    Delivered on Christmas Day via email
                </li>
                <li>
                    <span class="icon included">✅</span>
                    Printable PDF format
                </li>
                <li>
                    <span class="icon included">✅</span>
                    Letter from Santa
                </li>
                <li>
                    <span class="icon excluded">❌</span>
                    <span style="opacity: 0.6;">No cartoon photo of child</span>
                </li>
                <li>
                    <span class="icon excluded">❌</span>
                    <span style="opacity: 0.6;">No printable certificate</span>
                </li>
            </ul>

            <a href="{{ route('letter.create', ['tier' => 'free']) }}" class="btn cta-button btn-free">
                🎁 Get FREE E-Book
            </a>

            <p class="text-center mt-3 mb-0">
                <small class="text-muted">No payment required • No credit card needed</small>
            </p>
        </div>
    </div>

    <!-- PREMIUM Tier -->
    <div class="col-lg-6 mb-4">
        <div class="pricing-card premium">
            <div class="pricing-header">
                <h2 class="pricing-title">
                    ⭐ Premium Package
                </h2>
                <div class="pricing-price">
                    <small>$9.49</small> $4.99
                </div>
                <div class="savings-badge">
                    Save 48% - $4.50 OFF!
                </div>
                <p class="mt-2 mb-0">
                    <small style="color: #666;">
                        <i class="fas fa-globe"></i>
                        Approx: <strong>$3.19 USD</strong> · <strong>£2.54 GBP</strong> · <strong>€2.99 EUR</strong>
                    </small>
                </p>
            </div>

            <ul class="features-list">
                <li>
                    <span class="icon included">✅</span>
                    <strong>Everything in Free Basic</strong>
                </li>
                <li>
                    <span class="icon included">✅</span>
                    <strong style="color: var(--christmas-red);">Cartoon photo of YOUR child in the story! 📸</strong>
                </li>
                <li>
                    <span class="icon included">✅</span>
                    Personalized printable certificate
                </li>
                <li>
                    <span class="icon included">✅</span>
                    Premium story version
                </li>
                <li>
                    <span class="icon included">✅</span>
                    Priority customer support
                </li>
                <li>
                    <span class="icon included">✅</span>
                    High-resolution PDF
                </li>
                <li>
                    <span class="icon included">✅</span>
                    <strong>Photos auto-deleted after 7 days 🔒</strong>
                </li>
            </ul>

            <a href="{{ route('letter.create', ['tier' => 'premium']) }}" class="btn cta-button btn-premium">
                ⭐ Get Premium $4.99
            </a>

            <p class="text-center mt-3 mb-0">
                <small class="text-muted">Secure payment via Stripe • Money-back guarantee</small>
            </p>
        </div>
    </div>
</div>

<!-- Why Choose Premium -->
<div class="row mb-5">
    <div class="col-lg-8 mx-auto">
        <div class="card" style="background: linear-gradient(135deg, #fff8dc, #ffffff); border: 2px solid var(--christmas-red); border-radius: 20px;">
            <div class="card-body p-4 text-center">
                <h3 class="santa-font mb-3" style="color: var(--christmas-red);">
                    🌟 Why 87% Choose Premium
                </h3>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div style="font-size: 3rem;">📸</div>
                        <strong>See YOUR child</strong>
                        <p class="mb-0"><small>They become the star of the story!</small></p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div style="font-size: 3rem;">🎁</div>
                        <strong>More Special</strong>
                        <p class="mb-0"><small>Extra features make it unforgettable</small></p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div style="font-size: 3rem;">💝</div>
                        <strong>Only $4.99</strong>
                        <p class="mb-0"><small>Less than a coffee!</small></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- FAQ Section -->
<div class="row mb-5">
    <div class="col-lg-10 mx-auto">
        <h2 class="santa-font text-center mb-4" style="color: white; text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">
            ❓ Frequently Asked Questions
        </h2>
        <div class="accordion" id="faqAccordion">
            <div class="accordion-item mb-3" style="border-radius: 10px; border: none;">
                <h3 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                        <strong>Is the Free version really completely free?</strong>
                    </button>
                </h3>
                <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Yes! 100% free. No credit card required, no hidden fees. Just enter your details and you'll receive your personalized e-book on Christmas Day.
                    </div>
                </div>
            </div>

            <div class="accordion-item mb-3" style="border-radius: 10px; border: none;">
                <h3 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                        <strong>How long is this Black Friday deal?</strong>
                    </button>
                </h3>
                <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        This special offer is valid from November 29 - December 2, 2024 only. After that, prices return to normal ($29.49 regular).
                    </div>
                </div>
            </div>

            <div class="accordion-item mb-3" style="border-radius: 10px; border: none;">
                <h3 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                        <strong>Can I upgrade from Free to Premium later?</strong>
                    </button>
                </h3>
                <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Unfortunately no - you'll need to create a new order. We recommend choosing Premium now to get the cartoon photo feature at the Black Friday price!
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Countdown Timer Script -->
<script>
// Set Black Friday end date
const blackFridayEnd = new Date('2024-12-02T23:59:59').getTime();

function updateCountdown() {
    const now = new Date().getTime();
    const distance = blackFridayEnd - now;

    if (distance < 0) {
        document.getElementById('countdown').innerHTML = '<p class="text-white">Black Friday Sale Ended</p>';
        return;
    }

    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

    document.getElementById('days').textContent = String(days).padStart(2, '0');
    document.getElementById('hours').textContent = String(hours).padStart(2, '0');
    document.getElementById('minutes').textContent = String(minutes).padStart(2, '0');
    document.getElementById('seconds').textContent = String(seconds).padStart(2, '0');
}

// Update countdown every second
updateCountdown();
setInterval(updateCountdown, 1000);
</script>
@endsection
