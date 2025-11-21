@extends('layout')

@section('title', 'Contact Us - Letters2Santa')

@section('content')
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-body p-5">
                    <div class="text-center mb-5">
                        <h1 class="santa-font" style="color: var(--christmas-red); font-size: 3rem;">
                            📧 Contact Us
                        </h1>
                        <p class="lead text-muted">
                            We're here to help! Get in touch with Santa's Workshop team.
                        </p>
                    </div>

                    <!-- Contact Methods -->
                    <div class="row mb-5">
                        <div class="col-md-6 mb-4">
                            <div class="text-center p-4" style="background: linear-gradient(135deg, #f8f9fa, #e9ecef); border-radius: 12px; height: 100%;">
                                <div style="font-size: 3rem; color: var(--christmas-red); margin-bottom: 15px;">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <h3 class="h5 mb-3">Email Us</h3>
                                <p class="mb-2"><strong>General Inquiries:</strong></p>
                                <p><a href="mailto:workshop@letters2santa.com" style="font-size: 1.1rem;">workshop@letters2santa.com</a></p>
                                <p class="text-muted mt-3 mb-0">
                                    <small><i class="fas fa-clock"></i> We typically respond within 24 hours</small>
                                </p>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="text-center p-4" style="background: linear-gradient(135deg, #f8f9fa, #e9ecef); border-radius: 12px; height: 100%;">
                                <div style="font-size: 3rem; color: var(--christmas-green); margin-bottom: 15px;">
                                    <i class="fas fa-headset"></i>
                                </div>
                                <h3 class="h5 mb-3">Customer Support</h3>
                                <p class="mb-2"><strong>Need help with your order?</strong></p>
                                <p class="mb-3">Include your <strong>Order ID</strong> in your email</p>
                                <p class="text-muted mb-0">
                                    <small><i class="fas fa-info-circle"></i> Order ID format: L2S-XXXXX</small>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ Section -->
                    <div class="mb-5">
                        <h2 class="santa-font text-center mb-4" style="color: var(--christmas-green); font-size: 2rem;">
                            Frequently Asked Questions
                        </h2>

                        <div class="accordion" id="faqAccordion">
                            <!-- FAQ 1 -->
                            <div class="accordion-item mb-3" style="border: none; border-radius: 8px; overflow: hidden;">
                                <h3 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1" style="background: #f8f9fa; font-weight: 600;">
                                        <i class="fas fa-question-circle me-2" style="color: var(--christmas-red);"></i>
                                        When will I receive the e-book?
                                    </button>
                                </h3>
                                <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        Your personalised e-book will be delivered via email on <strong>Christmas Day morning</strong> (December 25th). Make sure your mobile number is active and check your email
                                        inbox (and spam folder).
                                    </div>
                                </div>
                            </div>

                            <!-- FAQ 2 -->
                            <div class="accordion-item mb-3" style="border: none; border-radius: 8px; overflow: hidden;">
                                <h3 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2" style="background: #f8f9fa; font-weight: 600;">
                                        <i class="fas fa-question-circle me-2" style="color: var(--christmas-red);"></i>
                                        Can I get a refund?
                                    </button>
                                </h3>
                                <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        Yes! Refunds are available if requested at least <strong>48 hours before Christmas Day</strong>. After the e-book is delivered, refunds are not available. Email us at <a
                                            href="mailto:workshop@letters2santa.com">workshop@letters2santa.com</a> with your Order ID to request a refund.
                                    </div>
                                </div>
                            </div>

                            <!-- FAQ 3 -->
                            <div class="accordion-item mb-3" style="border: none; border-radius: 8px; overflow: hidden;">
                                <h3 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3" style="background: #f8f9fa; font-weight: 600;">
                                        <i class="fas fa-question-circle me-2" style="color: var(--christmas-red);"></i>
                                        I didn't receive my Email
                                    </button>
                                </h3>
                                <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        First, check:
                                        <ul>
                                            <li>Your email spam/junk folder</li>
 `<li>Your email promotions folder</li>
                                        </ul>
                                        If you still can't find it, email us at <a href="mailto:workshop@letters2santa.com">workshop@letters2santa.com</a> with your Order ID and we'll resend it immediately.
                                    </div>
                                </div>
                            </div>

                            <!-- FAQ 4 -->
                            <div class="accordion-item mb-3" style="border: none; border-radius: 8px; overflow: hidden;">
                                <h3 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4" style="background: #f8f9fa; font-weight: 600;">
                                        <i class="fas fa-question-circle me-2" style="color: var(--christmas-red);"></i>
                                        Is my child's photo safe?
                                    </button>
                                </h3>
                                <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        Absolutely! Photos are:
                                        <ul>
                                            <li>Stored in encrypted, secure cloud storage</li>
                                            <li><strong>Automatically deleted within 7 days</strong> after delivery</li>
                                            <li>Never shared with third parties</li>
                                            <li>Never used for marketing or AI training</li>
                                        </ul>
                                        Your child's privacy and safety is our top priority.
                                    </div>
                                </div>
                            </div>

                            <!-- FAQ 5 -->
                            <div class="accordion-item mb-3" style="border: none; border-radius: 8px; overflow: hidden;">
                                <h3 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5" style="background: #f8f9fa; font-weight: 600;">
                                        <i class="fas fa-question-circle me-2" style="color: var(--christmas-red);"></i>
                                        Can I purchase multiple letters?
                                    </button>
                                </h3>
                                <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        Yes! You can purchase as many letters as you need. Each child should have their own separate order so their e-book is personalised just for them. Simply submit a new letter for each
                                        child.
                                    </div>
                                </div>
                            </div>

                            <!-- FAQ 6 -->
                            <div class="accordion-item mb-3" style="border: none; border-radius: 8px; overflow: hidden;">
                                <h3 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq6" style="background: #f8f9fa; font-weight: 600;">
                                        <i class="fas fa-question-circle me-2" style="color: var(--christmas-red);"></i>
                                        Do I get a physical book?
                                    </button>
                                </h3>
                                <div id="faq6" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        The $9.49 service includes a <strong>digital e-book</strong> (PDF) that you can view on any device or print at home. Physical printed books are not included but may be offered as an
                                        optional upgrade in the future.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Business Information -->
                    <div class="card mb-4" style="background: linear-gradient(135deg, #e8f5e9, #ffffff);">
                        <div class="card-body p-4">
                            <h3 class="h5 mb-3" style="color: var(--christmas-green);">
                                <i class="fas fa-building"></i> Business Information
                            </h3>
                            <p class="mb-2"><strong>Company Name:</strong> Traxrift Pty Ltd</p>
                            <p class="mb-2"><strong>ABN:</strong> 49 648 079 841</p>
                            <p class="mb-2"><strong>Country:</strong> Australia</p>
                            <p class="mb-2"><strong>Email:</strong> <a href="mailto:workshop@letters2santa.com">workshop@letters2santa.com</a></p>
                            <p class="mb-0"><strong>Website:</strong> <a href="https://letters2santa.com">letters2santa.com</a></p>
                        </div>
                    </div>

                    <!-- Response Time -->
                    <div class="alert alert-info">
                        <i class="fas fa-clock"></i> <strong>Response Time:</strong> We aim to respond to all inquiries within <strong>24 hours</strong> (Monday-Friday). Weekend inquiries will be answered on the next
                        business day.
                    </div>

                    <!-- Support for Issues -->
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> <strong>Urgent Issues on Christmas Day?</strong><br>
                        If you don't receive your e-book on Christmas Day, email us immediately at <a href="mailto:workshop@letters2santa.com">workshop@letters2santa.com</a> with your <strong>Order ID</strong>. We monitor
                        our inbox on Christmas Day for urgent delivery issues.
                    </div>

                    <!-- Back Button -->
                    <div class="text-center mt-4">
                        <a href="{{ route('home') }}" class="btn btn-outline-christmas btn-lg">
                            <i class="fas fa-arrow-left"></i> Back to Home
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
