@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Edit Customer</h2>

        <form action="{{ route('customers.update', $customer->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT') <!-- Ensure you're using PUT for updates -->

            <div class="mb-3">
                <label for="customer_name">Customer Name</label>
                <input type="text" name="customer_name" class="form-control" value="{{ old('customer_name', $customer->customer_name) }}">
            </div>
            @error('customer_name')
                <div class="alert alert-danger">
                    {{ $message }}
                </div>
            @enderror

            <div class="mb-3">
                <label for="customer_id">Register ID</label>
                <input type="number" name="customer_id" class="form-control" value="{{ old('customer_id', $customer->customer_id) }}">
            </div>
            @error('customer_id')
                <div class="alert alert-danger">
                    {{ $message }}
                </div>
            @enderror

            <div class="mb-3">
                <label for="customer_phone">Customer Phone</label>
                <input type="text" name="customer_phone" class="form-control" value="{{ old('customer_phone', $customer->customer_phone) }}">
            </div>
            @error('customer_phone')
                <div class="alert alert-danger">
                    {{ $message }}
                </div>
            @enderror

            <div class="mb-3">
                <label for="customer_image">Customer Image</label>
                <input type="file" name="customer_image" class="form-control">
            </div>
            @error('customer_image')
                <div class="alert alert-danger">
                    {{ $message }}
                </div>
            @enderror

            <div class="mb-3">
                <label for="landlord_name">Landlord Name</label>
                <input type="text" name="landlord_name" class="form-control" value="{{ old('landlord_name', $customer->landlord_name) }}">
            </div>
            @error('landlord_name')
                <div class="alert alert-danger">
                    {{ $message }}
                </div>
            @enderror

            <div class="mb-3">
                <label for="location_id">Location</label>
                <select name="location_id" class="form-control">
                    <option value="">Select Location</option>
                    @foreach ($locations as $location)
                        <option value="{{ $location->id }}" {{ $customer->location_id == $location->id ? 'selected' : '' }}>
                            {{ $location->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            @error('location_id')
                <div class="alert alert-danger">
                    {{ $message }}
                </div>
            @enderror

            <div class="mb-3">
                <label for="location_details">Address Details</label>
                <input type="text" name="location_details" class="form-control" value="{{ old('location_details', $customer->location_details) }}">
            </div>
            @error('location_details')
                <div class="alert alert-danger">
                    {{ $message }}
                </div>
            @enderror

            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('customers.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection
