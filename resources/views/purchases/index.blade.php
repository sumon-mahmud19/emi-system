@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>ক্রয় তালিকা</h2>
            @can('purchase-create')
                <a href="{{ route('purchases.create') }}" class="btn btn-primary">নতুন ক্রয়</a>
            @endcan
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-3">
            <h5 class="text-muted">মোট ক্রয়: <strong>{{ $totalPurchases }}</strong></h5>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover text-center align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ক্রমিক</th>
                        <th>গ্রাহক</th>
                        <th>মোবাইল</th>
                        <th>লোকেশন</th>
                        <th>পণ্য</th>
                        <th>মডেল</th>
                        <th>মূল্য</th>
                        <th>ডাউন পেমেন্ট</th>
                        <th>EMI পরিকল্পনা</th>
                        <th>অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($purchases as $index => $purchase)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $purchase->customer->customer_name ?? 'N/A' }}</td>
                            <td>{{ $purchase->customer->customer_phone ?? 'N/A' }}</td>
                            <td>{{ $purchase->customer->location->name ?? 'N/A' }}</td>
                            <td>{{ $purchase->product->product_name ?? 'N/A' }}</td>
                            <td>{{ $purchase->model->model_name ?? 'N/A' }}</td>
                            <td>{{ number_format($purchase->sales_price, 2) }} ৳</td>
                            <td>{{ number_format($purchase->down_price, 2) }} ৳</td>
                            <td>{{ $purchase->emi_plan }} মাস</td>
                            <td>
                                @can('purchase-edit')
                                    <a href="{{ route('purchases.edit', $purchase->id) }}"
                                        class="btn btn-sm btn-warning">এডিট</a>
                                @endcan
                                @can('purchase-delete')
                                    <form action="{{ route('purchases.destroy', $purchase->id) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('আপনি কি নিশ্চিতভাবে মুছতে চান?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">ডিলিট</button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10">কোনো ক্রয় পাওয়া যায়নি।</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $purchases->links() }}
        </div>
    </div>
@endsection
