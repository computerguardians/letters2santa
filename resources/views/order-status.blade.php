@extends('layout')

@section('title', 'Order Status - Letters2Santa')

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-body p-5">
                <!-- Order Header -->
                <div class="text-center mb-4">
                    @if($letter->delivery_status === 'delivered')
                        <i class="fas fa-check-circle" style="font-size: 4rem; color: #28a745;"></i>
                        <h1 class="santa-font mt-3" style="color: var(--christmas-green);">
                            Delivered! 🎉
                        </h1>
                    @elseif($letter->payment_status === 'completed')
                        <i class="fas fa-clock" style="font-size: 4rem; color: var(--christmas-red);"></i>
                        <h1 class="santa-font mt-3" style="color: var(--christmas-red);">
                            Order Confirmed
                        </h1>
                    @else
                        <i class="fas fa-hourglass-half" style="font-size: 4rem; color: #ffc107;"></i>
                        <h1 class="santa-font mt-3" style="color: #ffc107;">
                            Processing...
                        </h1>
                    @endif
                </div>

                <!-- Order Details -->
                <div class="card mb-4" style="background: linear-gradient(135deg, #fff8dc, #ffffff);">
                    <div class="card-body">
                        <h4 class="santa-font mb-4" style="color: var(--christmas-red);">
                            <i class="fas fa-gift"></i> Order Details
                        </h4>

                        <div class="row mb-3">
                            <div class="col-sm-4 fw-bold text-muted">Order ID:</div>
                            <div class="col-sm-8">
                                <span class="badge bg-dark" style="font-size: 1rem;">{{ $letter->order_id }}</span>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-4 fw-bold text-muted">Child's Name:</div>
                            <div class="col-sm-8">{{ $letter->child_name }}</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-4 fw-bold text-muted">Age Range:</div>
                            <div class="col-sm-8">{{ $letter->age_range }} years old</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-4 fw-bold text-muted">Parent Name:</div>
                            <div class="col-sm-8">{{ $letter->parent_name }}</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-4 fw-bold text-muted">Email:</div>
                            <div class="col-sm-8">{{ $letter->parent_email }}</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-4 fw-bold text-muted">Mobile:</div>
                            <div class="col-sm-8">{{ $letter->parent_mobile }}</div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4 fw-bold text-muted">Order Date:</div>
                            <div class="col-sm-8">{{ $letter->created_at->format('F j, Y g:i A') }}</div>
                        </div>
                    </div>
                </div>

                <!-- Status Timeline -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h4 class="santa-font mb-4" style="color: var(--christmas-green);">
                            <i class="fas fa-tasks"></i> Order Status
                        </h4>

                        <div class="timeline">
                            <!-- Step 1: Order Placed -->
                            <div class="timeline-item {{ $letter->created_at ? 'completed' : '' }}">
                                <div class="timeline-icon">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <div class="timeline-content">
                                    <h5>Order Placed</h5>
                                    <p class="text-muted mb-0">{{ $letter->created_at->format('M j, Y g:i A') }}</p>
                                </div>
                            </div>

                            <!-- Step 2: Payment Confirmed -->
                            <div class="timeline-item {{ $letter->payment_status === 'completed' ? 'completed' : 'pending' }}">
                                <div class="timeline-icon">
                                    <i class="fas fa-credit-card"></i>
                                </div>
                                <div class="timeline-content">
                                    <h5>Payment Confirmed</h5>
                                    @if($letter->payment_status === 'completed')
                                        <span class="badge bg-success">Paid</span>
                                    @else
                                        <span class="badge bg-warning">Pending</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Step 3: E-book Being Prepared -->
                            <div class="timeline-item {{ $letter->payment_status === 'completed' ? 'completed' : 'pending' }}">
                                <div class="timeline-icon">
                                    <i class="fas fa-magic"></i>
                                </div>
                                <div class="timeline-content">
                                    <h5>E-book Being Prepared</h5>
                                    @if($letter->payment_status === 'completed')
                                        <p class="text-muted mb-0">Santa's elves are creating {{ $letter->child_name }}'s magical storybook!</p>
                                    @else
                                        <p class="text-muted mb-0">Waiting for payment confirmation</p>
                                    @endif
                                </div>
                            </div>

                            <!-- Step 4: Delivery Scheduled -->
                            <div class="timeline-item {{ $letter->delivery_status === 'delivered' ? 'completed' : ($letter->payment_status === 'completed' ? 'active' : 'pending') }}">
                                <div class="timeline-icon">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                                <div class="timeline-content">
                                    <h5>Delivery Scheduled</h5>
                                    @if($letter->delivery_status === 'delivered')
                                        <span class="badge bg-success">Delivered</span>
                                        <p class="text-muted mb-0">{{ $letter->delivered_at ? $letter->delivered_at->format('M j, Y g:i A') : '' }}</p>
                                    @elseif($letter->payment_status === 'completed')
                                        <p class="text-muted mb-0">
                                            <i class="fas fa-email"></i> Will be sent via email on <strong>Christmas Day morning</strong><br>
                                            <small>December 25th between 12:00 AM - 10:00 AM</small>
                                        </p>
                                    @else
                                        <p class="text-muted mb-0">Pending payment</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Delivery Information -->
                @if($letter->payment_status === 'completed' && $letter->delivery_status !== 'delivered')
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> <strong>What happens next?</strong><br>
                    <ul class="mb-0 mt-2">
                        <li>Your magical storybook is being prepared by Santa's elves</li>
                        <li>You'll receive it via email on <strong>Christmas Day morning</strong></li>

                    </ul>
                </div>
                @endif

                @if($letter->delivery_status === 'delivered')
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <strong>Your magical storybook has been delivered!</strong><br>
                    <p class="mb-2 mt-2">Check your email for the download link.</p>
                    <p class="mb-0"><strong>Didn't receive it?</strong> Contact us at <a href="mailto:workshop@letters2santa.com">workshop@letters2santa.com</a></p>
                </div>
                @endif

                <!-- Photo Info -->
                @if($letter->child_photo)
                <div class="alert" style="background: #f8f9fa; border-left: 4px solid var(--christmas-green);">
                    <i class="fas fa-camera"></i> <strong>Photo Uploaded</strong><br>
                    <small class="text-muted">
                        A cartoon version of {{ $letter->child_name }} will appear in the storybook!<br>
                        <i class="fas fa-shield-alt"></i> Photo will be automatically deleted 7 days after delivery for security.
                    </small>
                </div>
                @endif

                <!-- Actions -->
                <div class="text-center mt-4">
                    <a href="{{ route('home') }}" class="btn btn-outline-christmas">
                        <i class="fas fa-arrow-left"></i> Back to Home
                    </a>
                    @if($letter->payment_status !== 'completed')
                    <a href="mailto:workshop@letters2santa.com?subject=Order {{ $letter->order_id }} - Payment Issue" class="btn btn-christmas ms-2">
                        <i class="fas fa-envelope"></i> Contact Support
                    </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Need Help Card -->
        <div class="card mt-4" style="background: linear-gradient(135deg, #f8f9fa, #e9ecef);">
            <div class="card-body p-4">
                <h5 class="mb-3">
                    <i class="fas fa-question-circle"></i> Need Help?
                </h5>
                <p class="mb-2">
                    <strong>Questions about your order?</strong><br>
                    <small>Email us at <a href="mailto:workshop@letters2santa.com">workshop@letters2santa.com</a> with your Order ID</small>
                </p>
                <p class="mb-0">
                    <strong>Want to order for another child?</strong><br>
                    <small><a href="{{ route('letter.create') }}">Create a new letter to Santa here</a></small>
                </p>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 40px;
}

.timeline-item {
    position: relative;
    padding-bottom: 30px;
}

.timeline-item:before {
    content: '';
    position: absolute;
    left: -29px;
    top: 30px;
    width: 2px;
    height: calc(100% - 20px);
    background: #dee2e6;
}

.timeline-item:last-child:before {
    display: none;
}

.timeline-item.completed .timeline-icon {
    background: #28a745;
    color: white;
}

.timeline-item.active .timeline-icon {
    background: var(--christmas-red);
    color: white;
}

.timeline-item.pending .timeline-icon {
    background: #e9ecef;
    color: #6c757d;
}

.timeline-icon {
    position: absolute;
    left: -40px;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    border: 3px solid white;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.timeline-content {
    padding-left: 20px;
}

.timeline-content h5 {
    margin-bottom: 5px;
    font-weight: 600;
}
</style>
@endsection
