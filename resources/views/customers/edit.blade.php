@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Customer</h2>

    <!-- Display validation errors -->
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form to edit customer -->
    <form action="{{ route('customers.update', $customer) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Customer Name -->
        <div class="mb-3">
            <label for="customer_name">Customer Name</label>
            <input type="text" name="customer_name" class="form-control" value="{{ old('customer_name', $customer->customer_name) }}" required>
        </div>

        <!-- Customer ID -->
        <div class="mb-3">
            <label for="customer_id">Customer ID</label>
            <input type="number" name="customer_id" class="form-control" value="{{ old('customer_id', $customer->customer_id) }}" required>
        </div>

        <!-- Customer Phone -->
        <div class="mb-3">
            <label for="customer_phone">Phone</label>
            <input type="text" name="customer_phone" class="form-control" value="{{ old('customer_phone', $customer->customer_phone) }}" required>
        </div>

        <!-- Location Dropdown -->
        <div class="mb-3">
            <label for="location_id">Location</label>
            <select name="location_id" class="form-control" required>
                @foreach($locations as $location)
                    <option value="{{ $location->id }}" {{ old('location_id', $customer->location_id) == $location->id ? 'selected' : '' }}>
                        {{ $location->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Landlord Name (optional) -->
        <div class="mb-3">
            <label for="landlord_name">Landlord Name (optional)</label>
            <input type="text" name="landlord_name" class="form-control" value="{{ old('landlord_name', $customer->landlord_name) }}">
        </div>

        <!-- Location Details (optional) -->
        <div class="mb-3">
            <label for="location_details">Location Details (optional)</label>
            <input type="text" name="location_details" class="form-control" value="{{ old('location_details', $customer->location_details) }}">
        </div>

        <!-- Customer Image -->
        <div class="mb-3">
            <label for="customer_image">Customer Image</label>
            <input type="file" name="customer_image" class="form-control">
            @if($customer->customer_image)
                <img src="{{ asset($customer->customer_image) }}" width="100" class="mt-2" alt="Customer Image">
            @endif
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Update Customer</button>
        <a href="{{ route('customers.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
