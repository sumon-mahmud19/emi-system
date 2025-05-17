@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-4">EMI Overview for {{ $customer->customer_name }}</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Payment Form -->
        <form action="{{ route('installments.pay-multiple') }}" method="POST">
            @csrf
            <input type="hidden" name="customer_id" value="{{ $customer->id }}">

            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Purchase ID</th>
                        <th>Product Name</th>
                        <th>Model</th>
                        <th>Total Price</th>
                        <th>Downpayment</th>
                        <th>Total Paid</th>
                        <th>Due Amount</th>
                        <th>Pay Now</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customer->purchases as $purchase)
                        @php
                            $product = $purchase->product;
                            $model = $purchase->model;
                            $totalPrice = $purchase->total_price;
                            $downPayment = $purchase->down_payment;
                            $totalPaid = $purchase->installments->sum('paid_amount');
                            $totalDue = $purchase->installments->sum(fn($i) => $i->amount - $i->paid_amount);
                        @endphp
                        <tr>
                            <td>{{ $purchase->id }}</td>
                            <td>{{ $product->product_name }}</td>
                            <td>{{ $model->model_name ?? 'N/A' }}</td>
                            <td>{{ number_format($totalPrice, 2) }} টাকা</td>
                            <td>{{ number_format($downPayment, 2) }} টাকা</td>
                            <td>{{ number_format($totalPaid, 2) }} টাকা</td>
                            <td>
                                <span class="text-danger">{{ number_format($totalDue, 2) }} টাকা</span>
                            </td>
                            <td>
                                <input type="number" name="payments[{{ $purchase->id }}]" class="form-control"
                                    value="0" min="0" max="{{ $totalDue }}" step="0.01"
                                    {{ $totalDue <= 0 ? 'disabled' : '' }}>
                            </td>
                            <td>
                                @can('installment-pay')
                                    @if (auth()->user()->hasRole('admin'))
                                        <button type="submit" class="btn btn-success btn-sm"
                                            {{ $totalDue <= 0 ? 'disabled' : '' }}>
                                            Pay
                                        </button>
                                    @endif
                                @endcan

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </form>

        <!-- Payment History Section -->
        <h3 class="mt-5">Payment History</h3>
        <table class="table table-striped">
            <thead class="table-secondary">
                <tr>
                    <th>Installment ID</th>
                    <th>Purchase ID</th>
                    <th>Product</th>
                    <th>Amount Paid</th>
                    <th>Paid At</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customer->purchases as $purchase)
                    @foreach ($purchase->installments as $installment)
                        <tr>
                            <td>{{ $installment->id }}</td>
                            <td>{{ $purchase->id }}</td>
                            <td>{{ $purchase->product->product_name }}</td>
                            <td>{{ number_format($installment->paid_amount, 2) }} টাকা</td>
                            <td>
                                {{ $installment->paid_at ? \Carbon\Carbon::parse($installment->paid_at)->format('Y-m-d H:i') : '—' }}
                            </td>
                            <td>
                                <span
                                    class="badge 
                                {{ $installment->status === 'paid'
                                    ? 'bg-success'
                                    : ($installment->status === 'partial'
                                        ? 'bg-warning'
                                        : 'bg-danger') }}">
                                    {{ ucfirst($installment->status) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No payments found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
