@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Add Purchase</h2>

    {{-- Global Error Display --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops! Something went wrong.</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="purchaseForm" action="{{ route('purchases.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Customer Dropdown -->
        <div class="mb-3">
            <label for="search" class="form-label">Select Customer</label>
            <select class="form-select select2 @error('customer_id') is-invalid @enderror"
                    id="search" name="customer_id" style="width: 100%;" required>
                @if (old('customer_id'))
                    <option value="{{ old('customer_id') }}" selected>Selected Customer</option>
                @endif
            </select>
            @error('customer_id')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <!-- Product Dropdown -->
        <div class="mb-3">
            <label for="product" class="form-label">Product</label>
            <select name="product_id" id="product" class="form-select @error('product_id') is-invalid @enderror" required>
                <option value="">Select Product</option>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                        {{ $product->product_name }}
                    </option>
                @endforeach
            </select>
            @error('product_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Model Dropdown -->
        <div class="mb-3">
            <label for="model" class="form-label">Model</label>
            <select name="model_id" id="model" class="form-select @error('model_id') is-invalid @enderror" required>
                <option value="">Select Model</option>
                {{-- Populated via AJAX --}}
            </select>
            @error('model_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Sales Price -->
        <div class="mb-3">
            <label class="form-label">Sales Price</label>
            <input type="number" name="sales_price" class="form-control @error('sales_price') is-invalid @enderror"
                value="{{ old('sales_price') }}" required>
            @error('sales_price')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Net Price -->
        <div class="mb-3">
            <label class="form-label">Net Price</label>
            <input type="number" name="net_price" class="form-control @error('net_price') is-invalid @enderror"
                value="{{ old('net_price') }}" required>
            @error('net_price')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Down Payment -->
        <div class="mb-3">
            <label class="form-label">Down Payment</label>
            <input type="number" name="down_price" class="form-control @error('down_price') is-invalid @enderror"
                value="{{ old('down_price') }}" required>
            @error('down_price')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- EMI Plan -->
        <div class="mb-3">
            <label class="form-label">EMI Plan (months)</label>
            <input type="number" name="emi_plan" class="form-control @error('emi_plan') is-invalid @enderror"
                value="{{ old('emi_plan') }}" required>
            @error('emi_plan')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Save Button -->
        <button type="submit" id="saveBtn" class="btn btn-primary d-flex align-items-center gap-2">
            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true" id="saveSpinner"></span>
            <span id="saveText">Save</span>
        </button>
        <a href="{{ route('purchases.index') }}" class="btn btn-secondary ms-2">Cancel</a>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function () {
        // Customer Search with Select2 AJAX
        $('#search').select2({
            placeholder: 'Search and Select Customer',
            ajax: {
                url: "{{ route('autocomplete') }}",
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: data.results
                    };
                },
                cache: true
            }
        });

        // Load Model on Product Change
        $('#product').on('change', function () {
            let productID = $(this).val();
            $('#model').empty().append('<option value="">Loading...</option>');

            if (productID) {
                $.get(`/get-models/${productID}`, function (data) {
                    $('#model').empty().append('<option value="">Select Model</option>');
                    $.each(data, function (_, model) {
                        $('#model').append(`<option value="${model.id}">${model.model_name}</option>`);
                    });

                    // Restore old selected model if exists
                    const oldModelId = "{{ old('model_id') }}";
                    if (oldModelId) {
                        $('#model').val(oldModelId);
                    }
                }).fail(function () {
                    $('#model').empty().append('<option value="">Select Model</option>');
                });
            } else {
                $('#model').empty().append('<option value="">Select Model</option>');
            }
        });

        // Submit Form via AJAX and Download PDF
        $('#purchaseForm').on('submit', async function (e) {
            e.preventDefault();

            const saveBtn = $('#saveBtn');
            const saveSpinner = $('#saveSpinner');
            const saveText = $('#saveText');

            saveBtn.prop('disabled', true);
            saveSpinner.removeClass('d-none');
            saveText.text('Saving...');

            const formData = new FormData(this);

            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/pdf'
                    }
                });

                if (!response.ok) throw new Error('Submission failed');

                const blob = await response.blob();
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;

                const disposition = response.headers.get('Content-Disposition');
                const filenameMatch = disposition?.match(/filename="?([^"]+)"?/);
                const filename = filenameMatch?.[1] || 'purchase.pdf';

                a.download = filename;
                document.body.appendChild(a);
                a.click();
                a.remove();
                URL.revokeObjectURL(url);
            } catch (error) {
                alert('Error: ' + error.message);
            } finally {
                saveBtn.prop('disabled', false);
                saveSpinner.addClass('d-none');
                saveText.text('Save');
            }
        });

        // Auto trigger model load if old product is selected
        @if(old('product_id'))
            $('#product').trigger('change');
        @endif
    });
</script>
@endpush
