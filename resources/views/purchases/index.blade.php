@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="mb-0">সকল ক্রয়</h2>

            @can('purchase-create')
                <a href="{{ route('purchases.create') }}" class="btn btn-success">নতুন ক্রয়</a>
            @endcan
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light text-center">
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
                        <tr data-bs-toggle="collapse" data-bs-target="#emiDetails{{ $purchase->id }}"
                            class="clickable text-center" style="cursor:pointer;">
                            <td>{{ $purchase->customer->customer_name }}</td>
                            <td>{{ $purchase->product->product_name }}</td>
                            <td>{{ $purchase->location->name ?? 'N/A' }}</td>
                            <td>{{ number_format($purchase->total_price, 2) }} ৳</td>
                            <td>{{ number_format($purchase->down_payment, 2) }} ৳</td>
                            <td>{{ $purchase->emi_plan }} মাস</td>
                            <td>

                                @can('purchase-edit')
                                    <a href="{{ route('purchases.edit', $purchase->id) }}"
                                        class="btn btn-sm btn-warning">এডিট</a>
                                @endcan

                                @can('purchase-delete')
                                    <form action="{{ route('purchases.destroy', $purchase->id) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('আপনি কি নিশ্চিতভাবে মুছতে চান?');">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger">ডিলিট</button>
                                    </form>
                                @endcan

                            </td>
                        </tr>
                        <tr class="collapse" id="emiDetails{{ $purchase->id }}">
                            <td colspan="7">
                                <strong>EMI বিস্তারিত:</strong>
                                <ul class="list-group mt-2">
                                    @foreach ($purchase->installments as $installment)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            {{ \Carbon\Carbon::parse($installment->due_date)->format('F Y') }}:
                                            <span>
                                                {{ number_format($installment->amount, 2) }} ৳ -
                                                <span class="{{ $installment->is_paid ? 'text-success' : 'text-danger' }}">
                                                    {{ $installment->is_paid ? 'পরিশোধিত' : 'বাকি' }}
                                                </span>
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
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
