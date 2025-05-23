@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h2 class="mb-4 text-center text-md-start">
            Customer Name: <strong>{{ $customer->customer_name }}</strong>
        </h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Purchase & EMI Summary --}}
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
    $grandTotalDownPayment = 0;  {{-- Initialize down payment sum --}}
@endphp

@foreach ($customer->purchases as $purchase)
    @php
        $product = $purchase->product;
        $totalPrice = $purchase->net_price;
        $total = $purchase->down_price;
        $totalPaid = $purchase->installments->sum('paid_amount');
        $totalDue = $purchase->installments->sum(fn($i) => $i->amount - $i->paid_amount);

        $grandTotalPrice += $totalPrice;
        $grandTotalPaid += $totalPaid;
        $grandTotalDue += $totalDue;
        $grandTotalDownPayment += $total; {{-- accumulate down payment --}}
    @endphp
    <tr>
        <!-- ... your table row ... -->
    </tr>
@endforeach

<tr class="fw-bold">
    <td colspan="7" class="p-3">
        <div
            class="bg-light rounded shadow-sm p-3 text-center d-flex flex-md-row justify-content-center align-items-center gap-4">
            <div>
                মোট মূল্য: <strong>{{ number_format($grandTotalPrice, 2) }} ৳</strong>
            </div>
            <div>
                মোট জমা: <strong>{{ number_format($grandTotalPaid + $grandTotalDownPayment, 2) }} ৳</strong>
            </div>
            <div>
                <strong class="{{ $grandTotalDue > 0 ? 'text-danger' : 'text-success' }}">
                    মোট বাকি: {{ number_format($grandTotalDue, 2) }} ৳
                </strong>
            </div>
        </div>
    </td>
</tr>


                            {{-- Totals Row --}}
                            <tr class="fw-bold">
                                <td colspan="7" class="p-3">
                                    <div
                                        class="bg-light rounded shadow-sm p-3 text-center d-flex flex-md-row justify-content-center align-items-center gap-4">
                                        <div>
                                            মোট মূল্য: <strong>{{ number_format($grandTotalPrice, 2) }} ৳</strong>
                                        </div>
                                        <div>
                                            মোট জমা: <strong>{{ number_format($grandTotalPaid + $total, 2) }} ৳</strong>
                                        </div>
                                        <div>
                                            <strong class="{{ $grandTotalDue > 0 ? 'text-danger' : 'text-success' }}">
                                                মোট বাকি: {{ number_format($grandTotalDue, 2) }} ৳
                                            </strong>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </form>

        {{-- Payment History with EMI Summary --}}
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white fw-bold fs-5 d-flex justify-content-between align-items-center">
                <span>Payment History</span>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle text-center mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 20%;">তারিখ</th>
                            <th style="width: 40%;">পণ্য</th>
                            <th style="width: 20%;">জমা (৳)</th>
                            @if (auth()->user()->hasRole('admin'))
                                <th style="width: 20%;">অ্যাকশন</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($paymentHistory as $payment)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($payment->paid_at)->format('d M Y') }}</td>
                                <td>{{ $payment->installment->purchase->product->product_name ?? 'N/A' }}</td>
                                <td class="text-success fw-semibold">{{ number_format($payment->amount, 2) }}</td>
                                @if (auth()->user()->hasRole('admin'))
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('payments.edit', $payment->id) }}"
                                                class="btn btn-warning btn-sm">
                                                Edit
                                            </a>
                                            <form action="{{ route('payments.destroy', $payment->id) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this payment?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm" type="submit">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ auth()->user()->hasRole('admin') ? 4 : 3 }}"
                                    class="text-center fst-italic text-muted py-4">
                                    কোন পেমেন্ট পাওয়া যায়নি।
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>


    </div>
@endsection
