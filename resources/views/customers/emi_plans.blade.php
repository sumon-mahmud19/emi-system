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

        <div class="card shadow-sm mb-4">
            <div class="card-header text-white" style="background-color: var(--bs-primary)">
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
                            $grandTotalDown = 0;
                        @endphp
                        @foreach ($customer->purchases as $purchase)
                            @php
                                $product = $purchase->product;
                                $totalPrice = $purchase->net_price;
                                $down = $purchase->down_price;
                                $totalPaid = $purchase->installments->sum('paid_amount');
                                $totalDue = $purchase->installments->sum(fn($i) => $i->amount - $i->paid_amount);
                                $totalDeposit = $totalPaid + $down;

                                $grandTotalPrice += $totalPrice;
                                $grandTotalPaid += $totalPaid;
                                $grandTotalDown += $down;
                                $grandTotalDue += $totalDue; // ✅ FIXED: added this line
                            @endphp
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($purchase->created_at)->format('d-m-Y') }}</td>
                                <td>{{ $product->product_name }}</td>
                                <td>{{ number_format($totalPrice, 2) }} ৳</td>
                                <td>{{ number_format($totalDeposit, 2) }} ৳</td>
                                <td>
                                    <span class="fw-bold {{ $totalDue > 0 ? 'text-danger' : 'text-success' }}">
                                        {{ number_format($totalDue, 2) }} ৳
                                    </span>
                                </td>

                                <form action="{{ route('installments.pay-multiple') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="customer_id" value="{{ $customer->id }}">

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
                                </form>

                                <form action="{{ route('purchases.destroy', $purchase->id) }}" method="POST"
                                    class="d-inline" onsubmit="return confirm('আপনি কি নিশ্চিতভাবে মুছতে চান?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger w-100">ডিলিট</button>
                                </form>
                        @endif
                        </td>
                        </tr>
                        @endforeach

                        {{-- Totals Row --}}
                        <tr class="fw-bold">
                            <td colspan="7" class="p-3">
                                <div
                                    class="bg-light rounded shadow-sm p-3 text-center d-flex flex-column flex-md-row justify-content-center align-items-center gap-3">
                                    <div>
                                        মোট মূল্য: <strong>{{ number_format($grandTotalPrice, 2) }} ৳</strong>
                                    </div>
                                    <div>
                                        মোট জমা: <strong>{{ number_format($grandTotalPaid + $grandTotalDown, 2) }}
                                            ৳</strong>
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


        {{-- Payment History --}}
        <div class="card shadow-sm">
          <div class="card-header text-white fw-bold fs-5" style="background-color: var(--bs-primary)">

                Payment History
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle text-center mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 20%;">তারিখ</th>
                            <th style="width: 30%;">পণ্য</th>
                            <th style="width: 20%;">জমা (৳)</th>
                            @role('admin')
                                <th style="width: 30%;">অ্যাকশন</th>
                            @endrole
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($paymentHistory as $payment)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($payment->paid_at)->format('d M Y') }}</td>
                                <td>{{ $payment->installment->purchase->product->product_name ?? 'N/A' }}</td>
                                <td class="text-success fw-semibold">{{ number_format($payment->amount, 2) }}</td>
                                @role('admin')
                                    <td>
                                        <a href="{{ route('payments.edit', $payment->id) }}"
                                            class="btn btn-sm btn-warning">এডিট</a>

                                        <form action="{{ route('payments.destroy', $payment->id) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('আপনি কি নিশ্চিতভাবে এই পেমেন্ট মুছতে চান?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">ডিলিট</button>
                                        </form>
                                    </td>
                                @endrole
                            </tr>
                        @empty
                            <tr>
                                <td colspan="@role('admin')4 @else 3 @endrole"
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
