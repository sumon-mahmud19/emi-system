@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>কাস্টমার এডিট করুন</h2>
        <form action="{{ route('customers.update', $customer->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="customer_name">কাস্টমারের নাম</label>
                        <input type="text" name="customer_name" class="form-control"
                            value="{{ old('customer_name', $customer->customer_name) }}">
                        @error('customer_name')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="customer_id">রেজিস্টার আইডি</label>
                        <input type="number" name="customer_id" class="form-control"
                            value="{{ old('customer_id', $customer->customer_id) }}">
                        @error('customer_id')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="customer_phone">মোবাইল নম্বর</label>
                        <input type="text" name="customer_phone" class="form-control"
                            value="{{ old('customer_phone', $customer->customer_phone) }}">
                        @error('customer_phone')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="landlord_name">বাড়িওয়ালার নাম</label>
                        <input type="text" name="landlord_name" class="form-control"
                            value="{{ old('landlord_name', $customer->landlord_name) }}">
                        @error('landlord_name')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="customer_image">কাস্টমারের ছবি</label>
                        <input type="file" name="customer_image" class="form-control" id="customer_image_input"
                            accept="image/*">
                        @error('customer_image')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror

                        <div class="mt-3">
                            <img id="image_preview"
                                src="{{ $customer->customer_image ? asset('storage/' . $customer->customer_image) : '#' }}"
                                alt="Preview" class="img-thumbnail {{ $customer->customer_image ? '' : 'd-none' }}"
                                style="width: 100px; height: 100px;">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="location_id">অবস্থান নির্বাচন করুন</label>
                        <select name="location_id" class="form-control">
                            <option value="">অবস্থান নির্বাচন করুন</option>
                            @foreach ($locations as $location)
                                <option value="{{ $location->id }}"
                                    {{ old('location_id', $customer->location_id) == $location->id ? 'selected' : '' }}>
                                    {{ $location->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('location_id')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="location_details">ঠিকানা</label>
                        <input type="text" name="location_details" class="form-control"
                            value="{{ old('location_details', $customer->location_details) }}">
                        @error('location_details')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between mt-3">
                <button type="submit" class="btn btn-primary">আপডেট করুন</button>
                <a href="{{ route('customers.index') }}" class="btn btn-secondary">বাতিল করুন</a>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('customer_image_input').addEventListener('change', function(e) {
            const reader = new FileReader();
            const imagePreview = document.getElementById('image_preview');

            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                imagePreview.classList.remove('d-none');
            };

            if (e.target.files.length > 0) {
                reader.readAsDataURL(e.target.files[0]);
            }
        });
    </script>
@endpush
