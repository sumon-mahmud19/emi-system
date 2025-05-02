@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Installment</h2>

    <form action="{{ route('installments.update', $installment) }}" method="POST">
        @csrf @method('PUT')

        <div class="mb-3">
            <label>Customer</label>
            <select name="customer_id" class="form-control">
                @foreach($customers as $customer)
                    <option value="{{ $customer->id }}" {{ $installment->customer_id == $customer->id ? 'selected' : '' }}>
                        {{ $customer->customer_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Product</label>
            <select name="product_id" class="form-control">
                @foreach($products as $product)
                    <option value="{{ $product->id }}" {{ $installment->product_id == $product->id ? 'selected' : '' }}>
                        {{ $product->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Amount</label>
            <input type="number" step="0.01" name="amount" class="form-control" value="{{ $installment->amount }}">
        </div>

        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control">
                @foreach(['pending', 'paid', 'overdue'] as $status)
                    <option value="{{ $status }}" {{ $installment->status == $status ? 'selected' : '' }}>
                        {{ ucfirst($status) }}
                    </option>
                @endforeach
            </select>
        </div>

        <button class="btn btn-success">Update</button>
    </form>
</div>
@endsection
