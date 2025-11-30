@extends('layouts.app')

@section('title', 'Create Purchase')

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
    <div class="container">
        <h2 class="mb-4">নতুন ক্রয় যুক্ত করুন</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>ত্রুটি!</strong> নিচের সমস্যাগুলো ঠিক করুন:
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="purchaseForm" action="{{ route('purchases.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="customer_id" class="form-label">গ্রাহক (Customer)</label>
                <select id="customer_id" name="customer_id" class="form-control select2" required>
                    <option value="">গ্রাহক নির্বাচন করুন</option>
                </select>
                @error('customer_id')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="product_id" class="form-label">পণ্য (Product)</label>
                <select id="product_id" name="product_id" class="form-select" required>
                    <option value="">পণ্য নির্বাচন করুন</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                    @endforeach
                </select>
                @error('product_id')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3" id="model_section" style="display: none;">
                <label for="model_id" class="form-label">মডেল (Model)</label>
                <select id="model_id" name="model_id" class="form-select">
                    <option value="">মডেল নির্বাচন করুন</option>
                </select>
                @error('model_id')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="sales_price" class="form-label">Net Price</label>
                <input type="number" name="sales_price" class="form-control" required step="0.01" min="0">
                @error('sales_price')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="net_price" class="form-label">Sales Price</label>
                <input type="number" name="net_price" class="form-control" required step="0.01" min="0">
                @error('net_price')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="down_price" class="form-label">ডাউন পেমেন্ট (Down Price)</label>
                <input type="number" name="down_price" class="form-control" required step="0.01" min="0">
                @error('down_price')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>



            <div class="mb-3">
                <label for="emi_plan" class="form-label">EMI Plan (মাস)</label>
                <input type="number" name="emi_plan" class="form-control" required min="1">
                @error('emi_plan')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">
                <span id="btn-text">Save Purchase</span>
                <span id="btn-spinner" class="spinner-border spinner-border-sm d-none"></span>
            </button>


        </form>
    </div>
@endsection


@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> {{-- ✅ jQuery CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // ✅ Initialize Select2 for customer autocomplete
            $('#customer_id').select2({
                ajax: {
                    url: "{{ route('autocomplete') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            term: params.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.map(item => ({
                                id: item.id,
                                text: item.customer_id + ' (' + item.customer_name + ')'
                            }))
                        };
                    },
                    cache: true
                },
                placeholder: 'গ্রাহকের নাম লিখুন...',
                minimumInputLength: 2
            });

            // ✅ Load models dynamically when product changes
            $('#product_id').on('change', function() {
                const productId = $(this).val();
                $('#model_id').empty().append('<option value="">লোড হচ্ছে...</option>');
                $('#model_section').hide();

                if (productId) {
                    $.ajax({
                        url: `/purchases/models/${productId}`,
                        method: 'GET',
                        success: function(models) {
                            if (models.length > 0) {
                                $('#model_id').empty().append(
                                    '<option value="">মডেল নির্বাচন করুন</option>');
                                models.forEach(model => {
                                    $('#model_id').append(
                                        `<option value="${model.id}">${model.model_name}</option>`
                                    );
                                });
                                $('#model_section').show(); // ✅ Show section
                            } else {
                                $('#model_id').empty().append(
                                    '<option value="">কোন মডেল পাওয়া যায়নি</option>');
                            }
                        },
                        error: function(xhr) {
                            console.error('Model load error:', xhr.responseText);
                            $('#model_id').empty().append(
                                '<option value="">লোড করতে ব্যর্থ</option>');
                        }
                    });
                }
            });
        });
    </script>


<script>
    document.getElementById('purchaseForm').addEventListener('submit', function (e) {
        const btnText = document.getElementById('btn-text');
        const btnSpinner = document.getElementById('btn-spinner');

        // Show spinner and disable form
        btnText.textContent = 'সংরক্ষণ হচ্ছে...';
        btnSpinner.classList.remove('d-none');
        this.querySelector('button').disabled = true;
    });
</script>

@endsection
