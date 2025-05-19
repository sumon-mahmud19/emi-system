{{-- @extends('layouts.app')

@section('content')
    <div class="container mt-3">
        <h2 class="mb-4 text-center">📊 Monthly EMI Report</h2>

        <!-- Month Picker Form -->
        
            <form method="GET" action="{{ route('monthly.reports') }}" class="row g-3 justify-content-center mb-5">
                <div class="col-md-3">
                    <input type="month" name="month" class="form-control" value="{{ request('month', now()->format('Y-m')) }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">🔎 Search</button>
                </div>
            </form>
       

        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card text-white bg-primary p-4 text-center shadow rounded-4">
                    <h5>Total Purchase</h5>
                    <h2>৳{{ number_format($totalPurchase, 2) }}</h2>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card text-white bg-success p-4 text-center shadow rounded-4">
                    <h5>Total Paid</h5>
                    <h2>৳{{ number_format($totalPaid, 2) }}</h2>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card text-white bg-danger p-4 text-center shadow rounded-4">
                    <h5>Total Due</h5>
                    <h2>৳{{ number_format($totalDue, 2) }}</h2>
                </div>
            </div>
        </div>

        <!-- Purchases Table -->
        <div class="row">
            <h4 class="mb-3">🛒 Purchases</h4>
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Product Name</th>
                            <th>Price (৳)</th>
                            <th>Purchase Date</th>
                            <th>Installments</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($purchases as $purchase)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $purchase->product->product_name ?? 'N/A' }}</td>
                                <td>৳{{ number_format($purchase->total_price, 2) }}</td>
                                <td>{{ $purchase->created_at->format('d M Y') }}</td>
                                <td>{{ $purchase->installments->count() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No purchases found for this month.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{ $purchases->links() }}
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const ctx = document.getElementById('summaryChart').getContext('2d');
        const summaryChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Total Purchase', 'Installments Paid', 'Total Due'],
                datasets: [{
                    label: 'Amount in ৳',
                    data: [{{ $totalPurchase }}, {{ $totalPaid }}, {{ $totalDue }}],
                    backgroundColor: [
                        'rgba(13, 110, 253, 0.8)', // Blue for Purchase
                        'rgba(25, 135, 84, 0.8)', // Green for Paid
                        'rgba(220, 53, 69, 0.8)', // Red for Due
                    ],
                    borderColor: [
                        'rgba(13, 110, 253, 1)',
                        'rgba(25, 135, 84, 1)',
                        'rgba(220, 53, 69, 1)',
                    ],
                    borderWidth: 2,
                    borderRadius: 10,
                    barThickness: 50,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '৳' + value;
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return '৳' + context.parsed.y;
                            }
                        }
                    }
                }
            }
        });
    </script>
@endsection --}}
