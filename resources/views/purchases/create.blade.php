@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Add Purchase</h2>

    <form action="{{ route('purchases.store') }}" method="POST">
        @csrf

        <!-- Customer Dropdown -->
        <div class="mb-3">
            <label for="customer" class="form-label">Customer</label>
            <select name="customer_id" id="customer" class="form-select select2">
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
            <label for="product" class="form-label">Product</label>
            <select name="product_id" id="product" class="form-select">
                <option value="">Select Product</option>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Model Dropdown -->
        <div class="mb-3">
            <label for="model" class="form-label">Model</label>
            <select name="model_id" id="model" class="form-select">
                <option value="">Select Model</option>
            </select>
        </div>

        <!-- Total Price -->
        <div class="mb-3">
            <label class="form-label">Total Price</label>
            <input type="number" name="total_price" class="form-control" step="0.01" required>
        </div>

        <!-- Sales Price -->
        <div class="mb-3">
            <label class="form-label">Sales Price</label>
            <input type="number" name="sales_price" class="form-control" step="0.01" required>
        </div>

        <!-- Down Payment -->
        <div class="mb-3">
            <label class="form-label">Down Payment</label>
            <input type="number" name="down_payment" class="form-control" step="0.01" required>
        </div>

        <!-- EMI Plan -->
        <div class="mb-3">
            <label class="form-label">EMI Plan (months)</label>
            <input type="number" name="emi_plan" class="form-control" required>
        </div>

        <!-- Buttons -->
        <button class="btn btn-primary">Save</button>
        <a href="{{ route('purchases.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection

@push('styles')
<!-- Select2 & Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--single {
        height: 38px;
        padding: 6px 12px;
        border: 1px solid #ced4da;
        border-radius: 4px;
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function () {
        $('#customer').select2({
            placeholder: 'Search Customer',
            allowClear: true,
            width: '100%'
        });

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
