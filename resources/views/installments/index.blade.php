@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Installment List</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Customer Name</th>
                <th>Product</th>
                <th>Purchase ID</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Due Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($installments as $index => $installment)
                <tr>
                    <td>{{ $installments->firstItem() + $index }}</td>
                    <td>{{ $installment->customer->customer_name ?? 'N/A' }}</td>
                    <td>{{ $installment->product->product_name ?? 'N/A' }}</td>
                    <td>#{{ $installment->purchase_id }}</td>
                    <td>৳{{ number_format($installment->amount, 2) }}</td>
                    <td>
                        <span class="badge bg-{{ $installment->status === 'paid' ? 'success' : 'warning' }}">
                            {{ ucfirst($installment->status) }}
                        </span>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($installment->due_date)->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('installments.show', $installment->id) }}" class="btn btn-sm btn-info">View</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">No Installments Found</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $installments->links() }}
    </div>
</div>
@endsection
