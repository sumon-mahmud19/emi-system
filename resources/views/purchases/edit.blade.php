@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">Edit Purchase</h2>

        <form action="{{ route('purchases.update', $purchase) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Customer -->
            <div class="mb-3">
                <label for="customer_id" class="form-label">Customer</label>
                <select name="customer_id" class="form-select" required>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}"
                            {{ $purchase->customer_id == $customer->id ? 'selected' : '' }}>
                            {{ $customer->customer_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Product -->
            <div class="mb-3">
                <label for="product_id" class="form-label">Product</label>
                <select name="product_id" id="product" class="form-select" required>
                    <option value="">Select Product</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}"
                            {{ $purchase->product_id == $product->id ? 'selected' : '' }}>
                            {{ $product->product_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Model (Dependent) -->
            <div class="mb-3">
                <label for="model_id" class="form-label">Model</label>
                <select name="model_id" id="model" class="form-select" required>
                    @foreach ($models as $model)
                        <option value="{{ $model->id }}"
                            {{ $purchase->model_id == $model->id ? 'selected' : '' }}>
                            {{ $model->model_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Sales Price -->
            <div class="mb-3">
                <label class="form-label">MRP Price</label>
                <input type="number" name="sales_price" class="form-control" value="{{ $purchase->sales_price }}" required>
            </div>

            <!-- Down Payment -->
            <div class="mb-3">
                <label class="form-label">Down Payment</label>
                <input type="number" name="down_price" class="form-control" value="{{ $purchase->down_price }}" required>
            </div>

            <!-- Net Price -->
            <div class="mb-3">
                <label class="form-label">Net Price</label>
                <input type="number" name="net_price" class="form-control" value="{{ $purchase->net_price }}" required>
            </div>

            <!-- EMI Plan -->
            <div class="mb-3">
                <label class="form-label">EMI Plan (months)</label>
                <input type="number" name="emi_plan" class="form-control" value="{{ $purchase->emi_plan }}" required>
            </div>

            <button class="btn btn-success">Update</button>
            <a href="{{ route('purchases.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        // Optional: Populate models dynamically based on selected product if needed
        $('#product').on('change', function() {
            let productID = $(this).val();
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
    </script>
@endpush
