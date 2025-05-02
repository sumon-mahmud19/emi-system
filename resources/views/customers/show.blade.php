@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Customers in {{ $location->name }}</h2>

    <form method="GET" action="{{ route('location.customers', $location->id) }}" class="row g-3 mb-4">
        <div class="col-md-3">
            <label for="start_date">Start Date</label>
            <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
        </div>
        <div class="col-md-3">
            <label for="end_date">End Date</label>
            <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button type="submit" class="btn btn-primary me-2">Filter</button>
            <a href="{{ route('location.customers', $location->id) }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    <table class="table table-bordered" id="customerTable">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Customer ID</th>
                <th>Phone</th>
                <th>Image</th>
                <th>Location</th>
                <th>Join-Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="customerBody">
            @foreach ($customers as $customer)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $customer->customer_name }}</td>
                    <td>{{ $customer->customer_id }}</td>
                    <td>{{ $customer->customer_phone }}</td>
                    
                    <td>
                        <a href="javascript:void(0);" class="show-customer-modal" data-bs-toggle="modal" data-bs-target="#customerModal"
                            data-name="{{ $customer->customer_name }}"
                            data-id="{{ $customer->customer_id }}"
                            data-phone="{{ $customer->customer_phone }}"
                            data-location="{{ $customer->location->name ?? 'N/A' }}"
                            data-image="{{ asset($customer->customer_image ?? 'images/default.png') }}">
                            <img src="{{ asset($customer->customer_image ?? 'images/default.png') }}"
                                class="img-fluid rounded-circle"
                                style="height: 50px; width: 50px; object-fit: cover;">
                        </a>
                    </td>
                    <td>{{ $customer->location->name ?? 'N/A' }}</td>
                    <td>{{ $customer->created_at->format('d-m-y h:i A') }}</td>

                    <td>
                        <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-warning btn-sm">Edit</a>

                        <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Are you sure?')">Delete</button>
                        </form>

                        <a href="{{ route('customers.emi_plans', $customer->id) }}" class="btn btn-sm btn-primary">EMI Details</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $customers->links() }}
</div>

<!-- Modal -->
<div class="modal fade" id="customerModal" tabindex="-1" aria-labelledby="customerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content p-3">
            <div class="modal-header">
                <h5 class="modal-title">Customer Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body row text-center text-md-start">
                <div class="col-md-6 d-flex flex-column justify-content-center">
                    <div>
                        <h5 id="modalCustomerName" class="mb-3"></h5>
                        <p><strong>Customer ID:</strong> <span id="modalCustomerID"></span></p>
                    </div>
                    <p><strong>Phone:</strong> <span id="modalCustomerPhone"></span></p>
                    <p><strong>Location:</strong> <span id="modalCustomerLocation"></span></p>
                </div>

                <div class="col-md-6 text-center text-md-end d-flex align-items-center justify-content-center justify-content-md-end">
                    <img id="modalCustomerImage" src="{{ asset('images/default.png') }}" alt="Customer Image"
                        class="img-fluid rounded shadow" style="max-height: 350px; max-width: 100%; object-fit: cover;">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(function () {
        $('#customerTable').on('click', '.show-customer-modal', function () {
            const name = $(this).data('name');
            const id = $(this).data('id');
            const phone = $(this).data('phone');
            const location = $(this).data('location');
            const image = $(this).data('image') || '{{ asset('images/default.png') }}';

            $('#modalCustomerName').text(name);
            $('#modalCustomerID').text(id);
            $('#modalCustomerPhone').text(phone);
            $('#modalCustomerLocation').text(location);
            $('#modalCustomerImage').attr('src', image);
        });
    });
</script>
@endpush
