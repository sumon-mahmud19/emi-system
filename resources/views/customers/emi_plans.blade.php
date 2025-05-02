@extends('layouts.app')

@section('content')
<div class="container">
    <h2>EMI Plans for {{ $customer->customer_name }}</h2>

    {{-- Flash message --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Payment Form -->
    <form action="{{ route('installments.pay-multiple') }}" method="POST">
        @csrf
        <input type="hidden" name="customer_id" value="{{ $customer->id }}">

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Purchase ID</th>
                    <th>Downpayment (Paid)</th>
                    <th>Total Due</th>
                    <th>Pay Now</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($customer->purchases as $purchase)
                    @php
                        $totalPaid = $purchase->installments->sum('paid_amount');
                        $totalDue = $purchase->installments->sum(fn($i) => $i->amount - $i->paid_amount);
                    @endphp
                    <tr>
                        <td>{{ $purchase->id }}</td>
                        <td>{{ number_format($totalPaid, 2) }}</td>
                        <td>{{ number_format($totalDue, 2) }}</td>
                        <td>
                            <input 
                                type="number" 
                                name="payments[{{ $purchase->id }}]" 
                                class="form-control"
                                value="0" 
                                min="0" 
                                max="{{ $totalDue }}" 
                                step="0.01"
                                {{ $totalDue <= 0 ? 'disabled' : '' }}
                            >
                        </td>
                        <td>
                            <button 
                                type="submit" 
                                class="btn btn-success"
                                {{ $totalDue <= 0 ? 'disabled' : '' }}
                            >
                                Pay
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </form>

    <!-- Payment History Section -->
    <h3 class="mt-5">Payment History</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Installment ID</th>
                <th>Purchase ID</th>
                <th>Amount Paid</th>
                <th>Paid At</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($customer->purchases as $purchase)
                @foreach($purchase->installments as $installment)
                    <tr>
                        <td>{{ $installment->id }}</td>
                        <td>{{ $purchase->id }}</td>
                        <td>{{ number_format($installment->paid_amount, 2) }}</td>
                        <td>{{ \Carbon\Carbon::parse($installment->paid_at)->format('Y-m-d H:i') }}</td>
                        <td>
                            <span class="badge 
                                {{ $installment->status === 'paid' ? 'bg-success' : 
                                   ($installment->status === 'partial' ? 'bg-warning' : 'bg-danger') }}">
                                {{ ucfirst($installment->status) }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            @empty
                <tr>
                    <td colspan="5">No payments found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
