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

                    <!-- Confirmation Message -->
                    <div class="alert alert-success mb-4">
                        <i class="fas fa-envelope-open-text"></i>
                        <strong>Payment Successful!</strong> Your order has been confirmed.
                    </div>

                    <!-- Order Details -->
                    <div class="card mb-4" style="background: linear-gradient(135deg, #fff8dc, #ffffff);">
                        <div class="card-body p-4">
                            <h4 class="santa-font mb-3" style="color: var(--christmas-red);">
                                <i class="fas fa-clipboard-list"></i> Order Details
                            </h4>
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <td class="text-start"><strong>Order ID:</strong></td>
                                    <td class="text-end"><code>{{ $letter->order_id }}</code></td>
                                </tr>
                                <tr>
                                    <td class="text-start"><strong>Child's Name:</strong></td>
                                    <td class="text-end">{{ $letter->child_name }}</td>
                                </tr>
                                <tr>
                                    <td class="text-start"><strong>Age Range:</strong></td>
                                    <td class="text-end">{{ $letter->age_range }} years</td>
                                </tr>
                                <tr>
                                    <td class="text-start"><strong>Delivery Phone:</strong></td>
                                    <td class="text-end">{{ $letter->parent_mobile }}</td>
                                </tr>
                                <tr>
                                    <td class="text-start"><strong>Confirmation Email:</strong></td>
                                    <td class="text-end">{{ $letter->parent_email }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

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
                                    <i class="fas fa-certificate" style="color: var(--christmas-red); font-size: 2rem;"></i>
                                    <h5 class="mt-3">4. Special Extras</h5>
                                    <p class="mb-0">Plus a printable certificate and a personal letter from Santa himself!</p>
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

                    <!-- Upsell Section -->
                    <div class="card mb-4" style="background: linear-gradient(135deg, var(--christmas-red), #8B0000);">
                        <div class="card-body p-4">
                            <h4 class="santa-font text-white mb-3">
                                <i class="fas fa-star"></i> Want Something Extra Special?
                            </h4>
                            <p class="text-white mb-3">
                                Make this Christmas even more magical with a beautiful printed hardcover book!
                            </p>
                            <button class="btn btn-lg" style="background: white; color: var(--christmas-red); font-weight: 600; padding: 12px 30px; border-radius: 50px;">
                                <i class="fas fa-book"></i> Order Printed Book - Coming Soon!
                            </button>
                            <p class="text-white mt-2 mb-0">
                                <small>Premium hardcover book delivered to your door</small>
                            </p>
                        </div>
                    </div>


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
                    <div class="d-flex gap-2 justify-content-center">
                        <button class="btn btn-outline-christmas">
                            <i class="fab fa-facebook"></i> Share on Facebook
                        </button>
                        <button class="btn btn-outline-christmas">
                            <i class="fab fa-whatsapp"></i> Share on WhatsApp
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extra-js')
    <script>
        // Optional: Add confetti or snow animation on success page
        console.log('Order {{ $letter->order_id }} confirmed!');
    </script>
@endsection
