@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Create Customer</h2>

    <form action="{{ route('customers.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="customer_name">Customer Name</label>
            <input type="text" name="customer_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="customer_id">Customer ID</label>
            <input type="number" name="customer_id" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="customer_phone">Customer Phone</label>
            <input type="text" name="customer_phone" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="customer_image">Customer Image</label>
            <input type="file" name="customer_image" class="form-control">
        </div>

        <div class="mb-3">
            <label for="landlord_name">Landlord Name</label>
            <input type="text" name="landlord_name" class="form-control">
        </div>

        <div class="mb-3">
            <label for="location_id">Location</label>
            <select name="location_id" class="form-control" required>
                <option value="">Select Location</option>
                @foreach ($locations as $location)
                    <option value="{{ $location->id }}">{{ $location->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="location_details">Location Details</label>
            <input type="text" name="location_details" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Save</button>
        <a href="{{ route('customers.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
