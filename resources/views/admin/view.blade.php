<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Letter - Letters2Santa Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f5f6fa; }
        .navbar { background: #C41E3A !important; }
        .detail-card {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .detail-row {
            padding: 12px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: 600;
            color: #666;
            width: 200px;
            display: inline-block;
        }
        .photo-container {
            text-align: center;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 12px;
        }
        .photo-container img {
            max-width: 400px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark mb-4">
        <div class="container-fluid">
            <a href="{{ route('admin.dashboard') }}" class="navbar-brand">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
            <a href="{{ route('admin.logout') }}" class="btn btn-light btn-sm">Logout</a>
        </div>
    </nav>

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="detail-card">
            <h3 class="mb-4"><i class="fas fa-envelope-open-text"></i> Letter Details</h3>

            <div class="detail-row">
                <span class="detail-label">Order ID:</span>
                <code style="font-size: 1.1rem;">{{ $letter->order_id }}</code>
            </div>

            <div class="detail-row">
                <span class="detail-label">Child's Name:</span>
                <strong>{{ $letter->child_name }}</strong>
            </div>

            <div class="detail-row">
                <span class="detail-label">Age Range:</span>
                {{ $letter->age_range }} years old
            </div>

                    <div class="detail-row">
                <span class="detail-label">Timezone:</span>
                {{ $letter->timezone }}
            </div>

            <div class="detail-row">
                <span class="detail-label">Parent's Name:</span>
                {{ $letter->parent_name }}
            </div>

            <div class="detail-row">
                <span class="detail-label">Parent's Email:</span>
                <a href="mailto:{{ $letter->parent_email }}">{{ $letter->parent_email }}</a>
            </div>

            <div class="detail-row">
                <span class="detail-label">Parent's Mobile:</span>
                <a href="tel:{{ $letter->parent_mobile }}">{{ $letter->parent_mobile }}</a>
            </div>

            <div class="detail-row">
                <span class="detail-label">Amount:</span>
                <strong>${{ number_format($letter->amount, 2) }} AUD</strong>
            </div>

            <div class="detail-row">
                <span class="detail-label">Payment Status:</span>
                @if($letter->payment_status == 'completed')
                    <span class="badge bg-success">Completed</span>
                @elseif($letter->payment_status == 'pending')
                    <span class="badge bg-warning">Pending</span>
                @else
                    <span class="badge bg-danger">Failed</span>
                @endif
            </div>

            <div class="detail-row">
                <span class="detail-label">Delivery Status:</span>
                @if($letter->delivery_status == 'sent')
                    <span class="badge bg-success">Sent</span>
                @elseif($letter->delivery_status == 'pending')
                    <span class="badge bg-warning">Pending</span>
                @else
                    <span class="badge bg-danger">Failed</span>
                @endif
            </div>

            @if($letter->stripe_payment_intent_id)
            <div class="detail-row">
                <span class="detail-label">Stripe Payment ID:</span>
                <code>{{ $letter->stripe_payment_intent_id }}</code>
            </div>
            @endif

            <div class="detail-row">
                <span class="detail-label">Order Date:</span>
                {{ $letter->created_at->format('F j, Y g:i A') }}
            </div>
        </div>

        <!-- Message to Santa -->
        <div class="detail-card">
            <h4 class="mb-3"><i class="fas fa-comment-dots"></i> Message to Santa</h4>
            <div class="p-3" style="background: #f8f9fa; border-radius: 8px; white-space: pre-wrap;">{{ $letter->message_to_santa }}</div>
        </div>

        <!-- Child Photo -->
        @if($photoUrl)
        <div class="detail-card">
            <h4 class="mb-3"><i class="fas fa-camera"></i> Child's Photo</h4>
            <div class="photo-container">
                <img src="{{ $photoUrl }}" alt="{{ $letter->child_name }}'s Photo" class="img-fluid">
                <div class="mt-3">
                    <a href="{{ $photoUrl }}" target="_blank" class="btn btn-primary">
                        <i class="fas fa-external-link-alt"></i> Open Full Size
                    </a>
                    <form method="POST" action="{{ route('admin.delete-photo', $letter->id) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this photo?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Delete Photo
                        </button>
                    </form>
                </div>
                <p class="mt-2 mb-0 text-muted">
                    <small><i class="fas fa-shield-alt"></i> Photo will be automatically deleted after e-book delivery</small>
                </p>
            </div>
        </div>
        @else
        <div class="detail-card">
            <p class="text-muted mb-0"><i class="fas fa-info-circle"></i> No photo uploaded for this letter</p>
        </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
