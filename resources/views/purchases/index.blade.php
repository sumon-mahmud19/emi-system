@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>সকল ক্রয়</h2>
        @can('purchase-create')
            <a href="{{ route('purchases.create') }}" class="btn btn-success">নতুন ক্রয়</a>
        @endcan
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="mb-3">
        <h5 class="text-muted">মোট ক্রয়: <strong>{{ $totalPurchases }}</strong></h5>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered align-middle text-center">
            <thead class="table-light">
                <tr>
                    <th>গ্রাহক</th>
                    <th>পণ্য</th>
                    <th>লোকেশন</th>
                    <th>মোট মূল্য</th>
                    <th>ডাউন পেমেন্ট</th>
                    <th>EMI পরিকল্পনা</th>
                    <th>অ্যাকশন</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($purchases as $purchase)
                    <tr data-bs-toggle="collapse" data-bs-target="#emiDetails{{ $purchase->id }}" class="clickable" style="cursor:pointer;">
                        <td>{{ $purchase->customer->customer_name ?? 'N/A' }}</td>
                        <td>{{ $purchase->product->product_name ?? 'N/A' }}</td>
                        <td>{{ $purchase->customer->location->name ?? 'N/A' }}</td>
                        <td>{{ number_format($purchase->total_price, 2) }} ৳</td>
                        <td>{{ number_format($purchase->down_payment, 2) }} ৳</td>
                        <td>{{ $purchase->emi_plan }} মাস</td>
                        <td>
                            @can('purchase-edit')
                                <a href="{{ route('purchases.edit', $purchase->id) }}" class="btn btn-sm btn-warning">এডিট</a>
                            @endcan
                            @can('purchase-delete')
                                <form action="{{ route('purchases.destroy', $purchase->id) }}" method="POST" class="d-inline" onsubmit="return confirm('আপনি কি নিশ্চিতভাবে মুছতে চান?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger">ডিলিট</button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                    <tr class="collapse bg-light" id="emiDetails{{ $purchase->id }}">
                        <td colspan="7">
                            <div class="card border-0 shadow-sm mt-3">
                                <div class="card-body">
                                    <h5 class="card-title text-primary">EMI বিস্তারিত</h5>
                                    @if ($purchase->installments->isEmpty())
                                        <p class="text-muted">এই ক্রয়ের কোনো EMI নেই।</p>
                                    @else
                                        <div class="row row-cols-1 row-cols-md-3 g-3">
                                            @foreach ($purchase->installments as $installment)
                                                <div class="col">
                                                    <div class="card h-100 border {{ $installment->is_paid ? 'border-success' : 'border-danger' }}">
                                                        <div class="card-body">
                                                            <h6 class="card-subtitle mb-2 text-muted">
                                                                {{ \Carbon\Carbon::parse($installment->due_date)->format('F Y') }}
                                                            </h6>
                                                            <p class="mb-1 fw-bold">{{ number_format($installment->amount, 2) }} ৳</p>
                                                            <span class="badge {{ $installment->is_paid ? 'bg-success' : 'bg-danger' }}">
                                                                {{ $installment->is_paid ? 'পরিশোধিত' : 'বাকি' }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">কোনো ক্রয় পাওয়া যায়নি।</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
