@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4 text-center">📊 EMI Dashboard</h2>

    <!-- Summary Cards -->
    <div class="row text-center mb-5">
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white shadow rounded-4 p-4">
                <h5>Total Purchase</h5>
                <h2>৳{{ number_format($totalPurchase, 2) }}</h2>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white shadow rounded-4 p-4">
                <h5>Customer Total Paid</h5>
                <h2>৳{{ number_format($totalPaid, 2) }}</h2>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card bg-danger text-white shadow rounded-4 p-4">
                <h5>Total Due</h5>
                <h2>৳{{ number_format($totalDue, 2) }}</h2>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-dark shadow rounded-4 p-4">
                <h5>Total Profit</h5>
                <h2>৳{{ number_format($totalProfit, 2) }}</h2>
            </div>
        </div>
    </div>

    <!-- Chart -->
    <div class="card shadow p-4 rounded-4 mb-5">
        <canvas id="summaryChart" style="height: 300px;"></canvas>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('summaryChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Total Purchase', 'Total Paid', 'Total Due', 'Total Profit'],
            datasets: [{
                label: 'Amount in ৳',
                data: [{{ $totalPurchase }}, {{ $totalPaid }}, {{ $totalDue }}, {{ $totalProfit }}],
                backgroundColor: [
                    'rgba(13, 110, 253, 0.8)',  // Blue
                    'rgba(25, 135, 84, 0.8)',   // Green
                    'rgba(220, 53, 69, 0.8)',   // Red
                    'rgba(255, 193, 7, 0.8)'    // Yellow
                ],
                borderColor: [
                    'rgba(13, 110, 253, 1)',
                    'rgba(25, 135, 84, 1)',
                    'rgba(220, 53, 69, 1)',
                    'rgba(255, 193, 7, 1)'
                ],
                borderWidth: 2,
                borderRadius: 10,
                barThickness: 50
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: value => '৳' + value
                    }
                }
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: context => '৳' + context.parsed.y
                    }
                }
            }
        }
    });
</script>
@endsection
