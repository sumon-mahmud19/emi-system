@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Purchase</h2>

    <form action="{{ route('purchases.update', $purchase) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Customer</label>
            <select name="customer_id" class="form-control">
                @foreach ($customers as $customer)
                    <option value="{{ $customer->id }}" {{ $purchase->customer_id == $customer->id ? 'selected' : '' }}>
                        {{ $customer->customer_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Product</label>
            <select name="product_id" class="form-control">
                @foreach ($products as $product)
                    <option value="{{ $product->id }}" {{ $purchase->product_id == $product->id ? 'selected' : '' }}>
                        {{ $product->product_name }}
                    </option>
                @endforeach
            </select>
        </div>


        <div class="mb-3">
            <label>Total Price</label>
            <input type="number" name="total_price" class="form-control" value="{{ $purchase->total_price }}" step="0.01">
        </div>

        <div class="mb-3">
            <label>Down Payment</label>
            <input type="number" name="down_payment" class="form-control" value="{{ $purchase->down_payment }}" step="0.01">
        </div>

        <div class="mb-3">
            <label>EMI Plan (months)</label>
            <input type="number" name="emi_plan" class="form-control" value="{{ $purchase->emi_plan }}">
        </div>

        <button class="btn btn-success">Update</button>
        <a href="{{ route('purchases.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
