@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h2 class="mb-4 text-center text-md-start">Customer Name:
            <strong>{{ $customer->customer_name }}</strong>
        </h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

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
                                <th>মোট</th>
                                <th>বাকি</th>
                                <th>কিস্তি</th>
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
                                    $totalPrice = $purchase->total_price;
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
                                            class="form-control form-control-sm" value="0" min="0"
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

                            <!-- Total Row -->
                            <!-- Total Row -->
                            <tr class="table table-striped table-hover fw-bold">
                                <td colspan="2" class="text-end">মোট = </td>
                                <td>{{ number_format($grandTotalPrice, 2) }} ৳</td>
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

        <!-- Payment History Section -->
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <strong>কিস্তির হিসাব</strong>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>তারিখ</th>
                            {{-- <th>Purchase ID</th> --}}
                            <th>পণ্য</th>
                            <th>মোট টাকা</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $count = 1; @endphp
                        @forelse($customer->purchases as $purchase)
                            @foreach ($purchase->installments as $installment)
                                @if ($installment->paid_amount > 0)
                                    <tr>
                                        {{-- <td>{{ $count++ }}</td> --}}
                                        <td>{{ \Carbon\Carbon::parse($installment->created_at)->format('d-m-Y') }}</td>
                                        <td>{{ $purchase->product->product_name }}</td>
                                        <td>{{ number_format($installment->paid_amount, 2) }} ৳</td>
                                        <td>
                                            <span
                                                class="badge 
                                            {{ $installment->status === 'paid'
                                                ? 'bg-success'
                                                : ($installment->status === 'partial'
                                                    ? 'bg-warning text-dark'
                                                    : 'bg-danger') }}">
                                                {{ ucfirst($installment->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="6" class="text-muted">No payments found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
