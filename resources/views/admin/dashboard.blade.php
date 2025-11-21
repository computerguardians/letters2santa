<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Letters2Santa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f5f6fa; }
        .navbar { background: #C41E3A !important; }
        .stat-card { 
            background: white; 
            padding: 20px; 
            border-radius: 12px; 
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .stat-card h3 { font-size: 2rem; margin: 0; color: #C41E3A; }
        .stat-card p { margin: 0; color: #666; }
        .table-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .badge-photo { background: #10b981; }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark mb-4">
        <div class="container-fluid">
            <span class="navbar-brand">🎅 Letters2Santa Admin</span>
            <a href="{{ route('admin.logout') }}" class="btn btn-light btn-sm">Logout</a>
        </div>
    </nav>

    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Stats -->
        <div class="row mb-4">
            <div class="col-md-2">
                <div class="stat-card">
                    <h3>{{ $stats['total'] }}</h3>
                    <p><i class="fas fa-envelope"></i> Total Letters</p>
                </div>
            </div>
            <div class="col-md-2">
                <div class="stat-card">
                    <h3>{{ $stats['completed'] }}</h3>
                    <p><i class="fas fa-check-circle"></i> Completed</p>
                </div>
            </div>
            <div class="col-md-2">
                <div class="stat-card">
                    <h3>{{ $stats['pending'] }}</h3>
                    <p><i class="fas fa-clock"></i> Pending</p>
                </div>
            </div>
            <div class="col-md-2">
                <div class="stat-card">
                    <h3>{{ $stats['failed'] }}</h3>
                    <p><i class="fas fa-times-circle"></i> Failed</p>
                </div>
            </div>
            <div class="col-md-2">
                <div class="stat-card">
                    <h3>${{ number_format($stats['total_revenue'], 2) }}</h3>
                    <p><i class="fas fa-dollar-sign"></i> Revenue</p>
                </div>
            </div>
            <div class="col-md-2">
                <div class="stat-card">
                    <h3>{{ $stats['with_photos'] }}</h3>
                    <p><i class="fas fa-camera"></i> With Photos</p>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="table-card mb-3">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-danger">Filter</button>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="table-card">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Child Name</th>
                            <th>Age</th>
                            <th>Parent</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Photo</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($letters as $letter)
                        <tr>
                            <td><code>{{ $letter->order_id }}</code></td>
                            <td><strong>{{ $letter->child_name }}</strong></td>
                            <td>{{ $letter->age_range }}</td>
                            <td>{{ $letter->parent_name }}</td>
                            <td>{{ $letter->parent_email }}</td>
                            <td>{{ $letter->parent_mobile }}</td>
                            <td>${{ number_format($letter->amount, 2) }}</td>
                            <td>
                                @if($letter->payment_status == 'completed')
                                    <span class="badge bg-success">Completed</span>
                                @elseif($letter->payment_status == 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @else
                                    <span class="badge bg-danger">Failed</span>
                                @endif
                            </td>
                            <td>
                                @if($letter->child_photo)
                                    <span class="badge badge-photo"><i class="fas fa-camera"></i> Yes</span>
                                @else
                                    <span class="badge bg-secondary">No</span>
                                @endif
                            </td>
                            <td>{{ $letter->created_at->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('admin.view', $letter->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center">No letters found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $letters->links() }}
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>