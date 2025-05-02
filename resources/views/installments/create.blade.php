@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create Installment</h1>
    <form action="{{ route('installments.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="customer_id">Customer</label>
            <select name="customer_id" id="customer_id" class="form-control" required>
                @foreach($customers as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->customer_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="product_id">Product</label>
            <select name="product_id" id="product_id" class="form-control" required>
                @foreach($purchases as $purchase)
                    <option value="{{ $purchase->id }}">{{ $purchase->product->product_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="amount">Amount</label>
            <input type="number" step="0.01" name="amount" id="amount" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" id="status" class="form-control" required>
                <option value="pending">Pending</option>
                <option value="paid">Paid</option>
            </select>
        </div>
        <div class="form-group">
            <label for="purchase_id">Purchase</label>
            <select name="purchase_id" id="purchase_id" class="form-control" required>
                @foreach($purchases as $purchase)
                    <option value="{{ $purchase->id }}">{{ $purchase->id }} - {{ $purchase->product->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-success">Create Installment</button>
    </form>
</div>
@endsection
