@extends('layouts.app')

@section('content')
<div class="container mt-3">
    <h2 class="mb-4 text-center">📊 EMI Report ({{ ucfirst($filter) }})</h2>

    <!-- Filter Form -->
    <form method="GET" action="{{ route('report') }}" class="row g-3 justify-content-center mb-5">
        <div class="col-md-3">
            <select name="filter" class="form-select">
                <option value="month" {{ $filter == 'month' ? 'selected' : '' }}>🗓️ মাস</option>
                <option value="week" {{ $filter == 'week' ? 'selected' : '' }}>📅 সপ্তাহ</option>
                <option value="year" {{ $filter == 'year' ? 'selected' : '' }}>📆 বছর</option>
            </select>
        </div>
        <div class="col-md-3">
            <input
                type="{{ $filter == 'year' ? 'number' : 'month' }}"
                name="date"
                class="form-control"
                value="{{ request('date', $filter == 'year' ? now()->year : now()->format('Y-m')) }}"
                placeholder="তারিখ নির্বাচন করুন"
            >
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">🔎 অনুসন্ধান</button>
        </div>
    </form>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-primary p-4 text-center shadow rounded-4">
                <h5>মোট ক্রয়</h5>
                <h2>৳{{ number_format($totalPurchase, 2) }}</h2>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card text-white bg-success p-4 text-center shadow rounded-4">
                <h5>পরিশোধিত</h5>
                <h2>৳{{ number_format($totalPaid, 2) }}</h2>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card text-white bg-danger p-4 text-center shadow rounded-4">
                <h5>বাকি</h5>
                <h2>৳{{ number_format($totalDue, 2) }}</h2>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card text-white bg-warning p-4 text-center shadow rounded-4">
                <h5>লাভ</h5>
                <h2>৳{{ number_format($profit, 2) }}</h2>
            </div>
        </div>
    </div>

    <!-- Purchases Table -->
    <div class="row">
        <h4 class="mb-3">🛒 ক্রয়সমূহ</h4>
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>পণ্যের নাম</th>
                        <th>মূল্য (৳)</th>
                        <th>তারিখ</th>
                        <th>ইএমআই সংখ্যা</th>
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
                            <td colspan="5" class="text-center">এই সময়ে কোনো ক্রয় পাওয়া যায়নি।</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $purchases->links() }}
        </div>
    </div>

    <!-- Chart Container -->
    <div class="mt-5" style="height: 400px;">
        <canvas id="summaryChart"></canvas>
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
            labels: ['মোট ক্রয়', 'পরিশোধিত', 'বাকি', 'লাভ'],
            datasets: [{
                label: 'টাকা (৳)',
                data: [{{ $totalPurchase }}, {{ $totalPaid }}, {{ $totalDue }}, {{ $profit }}],
                backgroundColor: [
                    'rgba(13, 110, 253, 0.8)', // Blue
                    'rgba(25, 135, 84, 0.8)',  // Green
                    'rgba(220, 53, 69, 0.8)',  // Red
                    'rgba(255, 193, 7, 0.8)'   // Yellow
                ],
                borderColor: [
                    'rgba(13, 110, 253, 1)',
                    'rgba(25, 135, 84, 1)',
                    'rgba(220, 53, 69, 1)',
                    'rgba(255, 193, 7, 1)'
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
@endsection
