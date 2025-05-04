@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Invoice</h2>

    <form action="{{ route('invoices.update', $invoice->id) }}" method="POST">
        @csrf
        @method('PUT')

        @foreach([
            'header_name', 'bill_to', 'name', 'phone', 'location', 'product_name',
            'product_model', 'total_price', 'down_payment', 'emi_month',
            'next_emi_amount', 'footer_name'
        ] as $field)
            <div class="mb-3">
                <label class="form-label text-capitalize">{{ str_replace('_', ' ', $field) }}</label>
                <input type="text" name="{{ $field }}" value="{{ old($field, $invoice->$field) }}"
                       class="form-control" required>
            </div>
        @endforeach

        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('invoices.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
w