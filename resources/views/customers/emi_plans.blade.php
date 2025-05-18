@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h2 class="mb-4">Customer Name: <strong>{{ $customer->customer_name }}</strong></h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Payment Form -->
        <form action="{{ route('installments.pay-multiple') }}" method="POST">
            @csrf
            <input type="hidden" name="customer_id" value="{{ $customer->id }}">

            <div class="card shadow-sm mb-5">
                <div class="card-header bg-primary text-white">
                    <strong>Purchase & EMI Summary</strong>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light text-center">
                            <tr>
                                <th>Purchase ID</th>
                                <th>Product</th>
                                <th>Model</th>
                                <th>Total Price</th>
                                <th>Downpayment</th>
                                <th>Total Paid</th>
                                <th>Due Amount</th>
                                <th>Pay Now</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-center align-middle">
                            @php
                                $grandTotalPaid = 0;
                                $grandTotalDue = 0;
                            @endphp
                            @foreach ($customer->purchases as $purchase)
                                @php
                                    $product = $purchase->product;
                                    $model = $purchase->model;
                                    $totalPrice = $purchase->total_price;
                                    $downPayment = $purchase->down_payment;
                                    $totalPaid = $purchase->installments->sum('paid_amount');
                                    $totalDue = $purchase->installments->sum(fn($i) => $i->amount - $i->paid_amount);
                                    $grandTotalPaid += $totalPaid;
                                    $grandTotalDue += $totalDue;
                                @endphp
                                <tr>
                                    <td>{{ $purchase->id }}</td>
                                    <td>{{ $product->product_name }}</td>
                                    <td>{{ $model->model_name ?? 'N/A' }}</td>
                                    <td>{{ number_format($totalPrice, 2) }} ৳</td>
                                    <td>{{ number_format($downPayment, 2) }} ৳</td>
                                    <td>{{ number_format($totalPaid, 2) }} ৳</td>
                                    <td>
                                        <span class="fw-bold {{ $totalDue > 0 ? 'text-danger' : 'text-success' }}">
                                            {{ number_format($totalDue, 2) }} ৳
                                        </span>
                                    </td>
                                    <td>
                                        <input type="number" name="payments[{{ $purchase->id }}]"
                                            class="form-control form-control-sm" value="0" min="0"
                                            max="{{ $totalDue }}" step="0.01"
                                            {{ $totalDue <= 0 ? 'disabled' : '' }}>
                                    </td>
                                    <td>
                                        @if (auth()->user()->hasRole('admin'))
                                            <button type="submit" class="btn btn-sm btn-success"
                                                {{ $totalDue <= 0 ? 'disabled' : '' }}>
                                                Pay
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach

                            <!-- ✅ Total Row -->
                            <tr class="table-dark fw-bold">
                                <td colspan="5" class="text-end">Total:</td>
                                <td>{{ number_format($grandTotalPaid, 2) }} ৳</td>
                                <td>
                                    <span class="{{ $grandTotalDue > 0 ? 'text-danger' : 'text-success' }}">
                                        {{ number_format($grandTotalDue, 2) }} ৳
                                    </span>
                                </td>
                                <td colspan="2"></td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </form>

       <!-- Payment History -->
<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <strong>Payment History</strong>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-hover mb-0">
            <thead class="table-light text-center">
                <tr>
                    <th>#</th>
                    <th>Purchase ID</th>
                    <th>Product</th>
                    <th>Amount Paid</th>
                    <th>Paid On</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody class="text-center align-middle">
                @php $count = 1; @endphp
                @forelse($customer->purchases as $purchase)
                    @foreach ($purchase->installments as $installment)
                        @if ($installment->paid_amount > 0)
                            <tr>
                                <td>{{ $count++ }}</td>
                                <td>{{ $purchase->id }}</td>
                                <td>{{ $purchase->product->product_name }}</td>
                                <td>{{ number_format($installment->paid_amount, 2) }} ৳</td>
                                <td>{{ \Carbon\Carbon::parse($installment->created_at)->format('Y-m-d H:i') }}</td>
                                <td>
                                    <span class="badge 
                                        {{ $installment->status === 'paid' ? 'bg-success' : ($installment->status === 'partial' ? 'bg-warning' : 'bg-danger') }}">
                                        {{ ucfirst($installment->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No payments found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

    </div>
@endsection
