@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-4">Customers</h2>

        <div
            class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-3">

            @can('customer-create')
                <a href="{{ route('customers.create') }}" class="btn btn-primary">Add Customer</a>
            @endcan

            <input type="text" id="liveSearch" class="form-control w-50 w-md-auto"
                placeholder="Search customer (e.g. shakil-fan-pl)">
        </div>

        <div class="mb-3">
            <strong>Total Results:</strong> <span id="resultCount">{{ $customers->total() }}</span>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered align-middle" id="customerTable">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Customer ID</th>
                        <th>Phone</th>
                        <th>Image</th>
                        <th>Location</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="customerBody">
                    @foreach ($customers as $customer)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $customer->customer_name }}</td>
                            <td>{{ $customer->customer_id }}</td>
                            <td>
                                <a href="tel:{{ $customer->customer_phone }}">{{ $customer->customer_phone }}</a>

                            </td>
                            <td>
                                <a class="show-customer-modal" data-bs-toggle="modal" data-bs-target="#customerModal"
                                    data-name="{{ $customer->customer_name }}" data-id="{{ $customer->customer_id }}"
                                    data-phone="tel:{{ $customer->customer_phone }}"
                                    data-location="{{ $customer->location->name ?? 'N/A' }}"
                                    data-image="{{ asset($customer->customer_image) }}">
                                    <img src="{{ asset($customer->customer_image ?? 'images/default.png') }}"
                                        class="img-fluid rounded-circle"
                                        style="height: 50px; width: 50px; object-fit: cover;">
                                </a>

                            </td>
                            <td>
                                <a
                                    href="{{ route('customers.show', $customer->location->id) }}">{{ $customer->location->name ?? 'N/A' }}</a>

                            </td>
                            <td>
                                @can('customer-edit')
                                    <a href="{{ route('customers.edit', $customer->id) }}"
                                        class="btn btn-warning btn-sm">Edit</a>
                                @endcan

                                @can('customer-delete')
                                    <form action="{{ route('customers.destroy', $customer->id) }}" method="POST"
                                        style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                @endcan


                                <a href="{{ route('customers.emi_plans', $customer->id) }}"
                                    class="btn btn-sm btn-primary">EMI Details</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination Links -->
            <div class="">

                {{ $customers->links() }}

            </div>
        </div>


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

                    <div
                        class="col-md-6 text-center text-md-end d-flex align-items-center justify-content-center justify-content-md-end">
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
        $(function() {
            // Live search
            let debounceTimer;

            $('#liveSearch').on('keyup', function() {
                const query = $(this).val();
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(function() {
                    $('#resultCount').text('Loading...');
                    $.ajax({
                        url: '{{ route('customers.index') }}',
                        method: 'GET',
                        data: {
                            search: query
                        },
                        success: function(response) {
                            $('#customerBody').html(response.html);
                            $('#resultCount').text(response.count + ' results');
                            $('#paginationLinks').html(response.pagination);
                        }
                    });
                }, 500);
            });

            // Modal population
            $('#customerTable').on('click', '.show-customer-modal', function() {
                const button = $(this);
                const name = button.data('name');
                const id = button.data('id');
                const phone = button.data('phone');
                const location = button.data('location');
                const image = button.data('image') || '{{ asset('images/default.png') }}';

                $('#modalCustomerName').text(name);
                $('#modalCustomerID').text(id);
                $('#modalCustomerPhone').text(phone);
                $('#modalCustomerLocation').text(location);
                $('#modalCustomerImage').attr('src', image);
            });
        });
    </script>
@endpush
