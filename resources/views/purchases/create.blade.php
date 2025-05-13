@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">Add Purchase</h2>

        <form action="{{ route('purchases.store') }}" method="POST">
            @csrf

            <!-- Customer Dropdown -->

            <form action="#" method="POST" enctype="multipart/form-data" class="mt-2">
                @csrf
       
                <select class="form-control" id="search" style="width:500px;" name="customer_id"></select>
       
                <div class="mb-3 mt-3">
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            
            </form>

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


@push('scripts')
    <script type="text/javascript">
        var path = "{{ route('autocomplete') }}";

        $('#search').select2({
            placeholder: 'Select an user',
            ajax: {
                url: path,
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                text: item.name,
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            }
        });
    </script>
@endpush
