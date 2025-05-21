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
                                $grandTotalPaid  = 0;
                                $grandTotalDue   = 0;
                            @endphp

                            @foreach ($customer->purchases as $purchase)
                                @php
                                    $product    = $purchase->product;
                                    $totalPrice = $purchase->sales_price;
                                    $totalPaid  = $purchase->installments->sum('paid_amount');
                                    $totalDue   = $purchase->installments->sum(fn($i) => $i->amount - $i->paid_amount);

                                    $grandTotalPrice += $totalPrice;
                                    $grandTotalPaid  += $totalPaid;
                                    $grandTotalDue   += $totalDue;
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
                                        <input type="number"
                                               name="payments[{{ $purchase->id }}]"
                                               class="form-control form-control-sm w-100"
                                               value="0"
                                               min="0"
                                               max="{{ $totalDue }}"
                                               step="0.01"
                                               {{ $totalDue <= 0 ? 'disabled' : '' }}>
                                    </td>
                                    <td>
                                        @if (auth()->user()->hasRole('admin'))
                                            <button type="submit"
                                                    class="btn btn-success btn-sm w-100"
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
                                                মোট জমা: <strong>{{ number_format($grandTotalPaid, 2) }} ৳</strong>
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

        {{-- Payment History Section --}}
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <strong>কিস্তির হিসাব</strong>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>তারিখ ও সময়</th>
                            <th>পণ্য</th>
                            <th>টোটাল পরিশোধিত</th>
                            <th>স্ট্যাটাস</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            // Gather all payments for this customer's installments
                            $installmentIds = $customer->purchases
                                ->flatMap(fn($p) => $p->installments->pluck('id'))
                                ->toArray();

                            $allPayments = \App\Models\Payment::with('installment.purchase.product')
                                ->whereIn('installment_id', $installmentIds)
                                ->orderBy('paid_at', 'desc')
                                ->get();

                            // Group by exact paid_at timestamp
                            $groups = $allPayments->groupBy(fn($p) => $p->paid_at->date('Y-m-d'));
                        @endphp

                        @forelse($groups as $paidAtString => $paymentsOnThatGroup)
                            @php
                                // Parse date
                                $paidAt = \Carbon\Carbon::parse($paidAtString);

                                // Use first payment to find purchase & product
                                $first   = $paymentsOnThatGroup->first();
                                $purchase = $first->installment->purchase;
                                $product  = $purchase->product->product_name;

                                // Sum this group's payments
                                $totalAmount = $paymentsOnThatGroup->sum('amount');

                                // Overall status: paid if all installments paid
                                $allPaid = $purchase->installments->every(fn($inst) => $inst->status === 'paid');
                                $status  = $allPaid ? 'Paid' : 'Partial';
                                $badge   = $allPaid ? 'bg-success' : 'bg-warning text-dark';
                            @endphp
                            <tr>
                                <td>{{ $paidAt->format('d-m-Y H:i') }}</td>
                                <td>{{ $product }}</td>
                                <td>{{ number_format($totalAmount, 2) }} ৳</td>
                                <td>
                                    <span class="badge {{ $badge }}">{{ $status }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-muted">কোনো পেমেন্ট পাওয়া যায়নি।</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
