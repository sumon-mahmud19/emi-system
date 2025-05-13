@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">Add Purchase</h2>

        <form action="{{ route('purchases.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Customer Dropdown (Select2 AJAX) -->
            <div class="mb-3">
                <label for="search" class="form-label">Select Customer</label>
                <select class="form-select select2" id="search" name="customer_id" style="width:100%;">
                    <option value="">Search and Select Customer</option>
                </select>
            </div>

            <!-- Product Dropdown -->
            <div class="mb-3">
                <label for="product" class="form-label">Product</label>
                <select name="product_id" id="product" class="form-select" required>
                    <option value="">Select Product</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Model Dropdown (Dependent) -->
            <div class="mb-3">
                <label for="model" class="form-label">Model</label>
                <select name="model_id" id="model" class="form-select" required>
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
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('purchases.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection

@push('styles')
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@push('scripts')
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script type="text/javascript">
        // Select2 Customer Search
        $('#search').select2({
            placeholder: 'Search and Select Customer',
            ajax: {
                url: "{{ route('autocomplete') }}",
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                text: item.customer_name + ' (' + item.customer_phone + ')',
                                id: item.id
                            };
                        })
                    };
                },
                cache: true
            }
        });

        // Product -> Model dependent dropdown
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
