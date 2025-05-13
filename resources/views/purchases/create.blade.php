@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Add Purchase</h2>

    <form action="{{ route('purchases.store') }}" method="POST">
        @csrf

        <!-- Customer Dropdown (Searchable) -->
        <div class="mb-3">
            <label>Customer</label>
            <select name="customer_id" id="customer" class="form-control select2">
                <option value="">Select Customer</option>
                @foreach ($customers as $customer)
                    <option value="{{ $customer->id }}">
                        {{ $customer->customer_name }} ({{ $customer->customer_phone }})
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Product Dropdown -->
        <div class="mb-3">
            <label>Product</label>
            <select name="product_id" id="product" class="form-control">
                <option value="">Select Product</option>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Model Dropdown (Dependent) -->
        <div class="mb-3">
            <label>Model</label>
            <select name="model_id" id="model" class="form-control">
                <option value="">Select Model</option>
            </select>
        </div>

        <!-- Total Price Input -->
        <div class="mb-3">
            <label>Total Price</label>
            <input type="number" name="total_price" class="form-control" step="0.01" required>
        </div>

        <!-- Sales Price Input -->
        <div class="mb-3">
            <label>Sales Price</label>
            <input type="number" name="sales_price" class="form-control" step="0.01" required>
        </div>

        <!-- Down Payment Input -->
        <div class="mb-3">
            <label>Down Payment</label>
            <input type="number" name="down_payment" class="form-control" step="0.01" required>
        </div>

        <!-- EMI Plan Input -->
        <div class="mb-3">
            <label>EMI Plan (months)</label>
            <input type="number" name="emi_plan" class="form-control" required>
        </div>

        <!-- Submit and Cancel Buttons -->
        <button class="btn btn-primary">Save</button>
        <a href="{{ route('purchases.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function () {
        // Enable select2 for customer
        $('#customer').select2({
            placeholder: 'Search Customer',
            allowClear: true,
            width: '100%'
        });

        // Dependent model dropdown
        $('#product').on('change', function() {
            var productID = $(this).val();
            if (productID) {
                $.ajax({
                    url: '/get-models/' + productID,
                    type: 'GET',
                    success: function(data) {
                        $('#model').empty().append('<option value="">Select Model</option>');
                        $.each(data, function(index, model) {
                            $('#model').append('<option value="' + model.id + '">' + model.model_name + '</option>');
                        });
                    }
                });
            } else {
                $('#model').empty().append('<option value="">Select Model</option>');
            }
        });
    });
</script>
@endpush
