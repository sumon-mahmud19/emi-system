@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>কাস্টমার তৈরি করুন</h2>

        <form action="{{ route('customers.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="customer_name">কাস্টমারের নাম</label>
                        <input type="text" name="customer_name" class="form-control" value="{{ old('customer_name') }}">
                        @error('customer_name')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="customer_id">রেজিস্টার আইডি</label>
                        <input type="number" name="customer_id" class="form-control" value="{{ old('customer_id') }}">
                        @error('customer_id')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="customer_phone">মোবাইল নম্বর</label>
                        <input type="text" name="customer_phone" class="form-control" value="{{ old('customer_phone') }}">
                        @error('customer_phone')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="customer_phone2">মোবাইল নম্বর 2</label>
                        <input type="text" name="customer_phone2" class="form-control" value="{{ old('customer_phone2') }}">
                        @error('customer_phone2')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="landlord_name">বাড়িওয়ালার নাম</label>
                        <input type="text" name="landlord_name" class="form-control" value="{{ old('landlord_name') }}">
                        @error('landlord_name')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="customer_image">কাস্টমারের ছবি</label>
                        <input type="file" name="customer_image" class="form-control" id="customer_image_input" accept="image/*">
                        @error('customer_image')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                        <div class="mt-3">
                            <img id="image_preview" src="#" alt="Preview" class="img-thumbnail d-none" style="width: 100px; height: 100px;">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="location_id">অবস্থান নির্বাচন করুন</label>
                        <select name="location_id" class="form-control">
                            <option value="">অবস্থান নির্বাচন করুন</option>
                            @foreach ($locations as $location)
                                <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>
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
                        <input type="text" name="location_details" class="form-control" value="{{ old('location_details') }}">
                        @error('location_details')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between mt-3">
                <button type="submit" class="btn btn-primary">সেভ করুন</button>
                <a href="{{ route('customers.index') }}" class="btn btn-secondary">বাতিল করুন</a>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    document.getElementById('customer_image_input').addEventListener('change', function (e) {
        const reader = new FileReader();
        const imagePreview = document.getElementById('image_preview');

        reader.onload = function (e) {
            imagePreview.src = e.target.result;
            imagePreview.classList.remove('d-none');
        };

        if (e.target.files.length > 0) {
            reader.readAsDataURL(e.target.files[0]);
        }
    });
</script>
@endpush
