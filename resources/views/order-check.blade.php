@extends('layout')

@section('title', 'Check Your Order - Letters2Santa')

@section('content')
<div class="row">
    <div class="col-lg-6 mx-auto">
        <div class="card">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <i class="fas fa-search" style="font-size: 3rem; color: var(--christmas-green);"></i>
                    <h1 class="santa-font mt-3" style="color: var(--christmas-red);">
                        Check Your Order
                    </h1>
                    <p class="text-muted">
                        Enter your order details to view the status
                    </p>
                </div>

                @if(session('error'))
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('order.verify') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="order_id" class="form-label fw-bold">
                            <i class="fas fa-hashtag"></i> Order ID <span class="text-danger">*</span>
                        </label>
                        <input 
                            type="text" 
                            class="form-control form-control-lg @error('order_id') is-invalid @enderror" 
                            id="order_id" 
                            name="order_id" 
                            value="{{ old('order_id') }}"
                            placeholder="L2S-XXXXXXXXXXXXX" 
                            required
                        >
                        @error('order_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle"></i> Found in your confirmation email
                        </small>
                    </div>

                    <div class="mb-4">
                        <label for="parent_email" class="form-label fw-bold">
                            <i class="fas fa-envelope"></i> Email Address <span class="text-danger">*</span>
                        </label>
                        <input 
                            type="email" 
                            class="form-control form-control-lg @error('parent_email') is-invalid @enderror" 
                            id="parent_email" 
                            name="parent_email" 
                            value="{{ old('parent_email') }}"
                            placeholder="your.email@example.com" 
                            required
                        >
                        @error('parent_email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle"></i> Email used when ordering
                        </small>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-christmas btn-lg">
                            <i class="fas fa-search"></i> Check Order Status
                        </button>
                    </div>
                </form>

                <div class="text-center mt-4">
                    <a href="{{ route('home') }}" class="btn btn-link">
                        <i class="fas fa-arrow-left"></i> Back to Home
                    </a>
                </div>
            </div>
        </div>

        <!-- Help Section -->
        <div class="card mt-4" style="background: linear-gradient(135deg, #f8f9fa, #e9ecef);">
            <div class="card-body p-4">
                <h5 class="mb-3">
                    <i class="fas fa-question-circle"></i> Need Help?
                </h5>
                <p class="mb-2">
                    <strong>Where can I find my Order ID?</strong><br>
                    <small>Your Order ID is in the confirmation email sent after purchase. It starts with "L2S-"</small>
                </p>
                <p class="mb-0">
                    <strong>Didn't receive your confirmation email?</strong><br>
                    <small>Check your spam folder or contact us at <a href="mailto:workshop@letters2santa.com">workshop@letters2santa.com</a></small>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
