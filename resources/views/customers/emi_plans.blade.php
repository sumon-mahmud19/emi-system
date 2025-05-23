@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h2 class="mb-4 text-center text-md-start">
            Customer Name: <strong>{{ $customer->customer_name }}</strong>
        </h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Payment Form --}}
        <form action="{{ route('installments.pay-multiple') }}" method="POST">
            @csrf
            <input type="hidden" name="customer_id" value="{{ $customer->id }}">

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <strong>Purchase & EMI Summary</strong>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle text-center">
                        <thead class="table-light">
                            <tr>
                                <th>তারিখ</th>
                                <th>পণ্য</th>
                                <th>মূল্য</th>
                                <th>জমা</th>
                                <th>বাকি</th>
                                <th style="min-width: 120px;">কিস্তি</th>
                                <th>অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $grandTotalPrice = 0;
                                $grandTotalPaid = 0;
                                $grandTotalDue = 0;
                            @endphp
                            @foreach ($customer->purchases as $purchase)
                                @php
                                    $product = $purchase->product;
                                    $totalPrice = $purchase->sales_price;
                                    $totalPaid = $purchase->installments->sum('paid_amount');
                                    $totalDue = $purchase->installments->sum(fn($i) => $i->amount - $i->paid_amount);
                                    $grandTotalPrice += $totalPrice;
                                    $grandTotalPaid += $totalPaid;
                                    $grandTotalDue += $totalDue;
                                @endphp
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($purchase->created_at)->format('d-m-Y') }}</td>
                                    <td>{{ $product->product_name }}</td>
                                    <td>{{ number_format($totalPrice, 2) }} ৳</td>
                                    <td>{{ number_format($totalPaid, 2) }} ৳</td>
                                    <td>
                                        <span class="fw-bold {{ $totalDue > 0 ? 'text-danger' : 'text-success' }}">
                                            {{ number_format($totalDue, 2) }} ৳
                                        </span>
                                    </td>
                                    <td>
                                        <input type="number" name="payments[{{ $purchase->id }}]"
                                            class="form-control form-control-sm w-100" value="0" min="0"
                                            max="{{ $totalDue }}" step="0.01"
                                            {{ $totalDue <= 0 ? 'disabled' : '' }}>
                                    </td>
                                    <td>
                                        @if (auth()->user()->hasRole('admin'))
                                            <button type="submit" class="btn btn-success btn-sm w-100"
                                                {{ $totalDue <= 0 ? 'disabled' : '' }}>
                                                Pay
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach

                            {{-- Totals Row --}}
                            <tr class="fw-bold">
                                <td colspan="7" class="p-3">
                                    <div class="row text-start">
                                        <div class="col-12 col-md-4 mb-2">
                                            <div class="bg-light rounded p-2 shadow-sm text-start text-md-center">
                                                মোট মূল্য: <strong>{{ number_format($grandTotalPrice, 2) }} ৳</strong>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4 mb-2">
                                            <div class="bg-light rounded p-2 shadow-sm text-start text-md-center">
                                                মোট জমা: <strong>{{ number_format($purchase->total_price + $grandTotalPaid, 2) }} ৳</strong>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="bg-light rounded p-2 shadow-sm text-start text-md-center">
                                                <strong class="{{ $grandTotalDue > 0 ? 'text-danger' : 'text-success' }}">
                                                    মোট বাকি: {{ number_format($grandTotalDue, 2) }} ৳
                                                </strong>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </form>


        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Product</th>
                    <th>Amount Paid (৳)</th>
                    {{-- <th>Installment ID</th> --}}
                </tr>
            </thead>
            <tbody>
                @forelse($paymentHistory as $payment)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($payment->paid_at)->format('d M Y') }}</td>
                        <td>{{ $payment->installment->purchase->product->product_name ?? 'N/A' }}</td>
                        <td>{{ number_format($payment->amount, 2) }}</td>
                        {{-- <td>{{ $payment->installment_id }}</td> --}}
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No payments found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>


    </div>
@endsection
