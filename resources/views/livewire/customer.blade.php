@extends('layouts.app')

@section('content')
    <div class="container">

        <h2 class="mb-4">Customers2</h2>

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-3 gap-3">
            @can('customer-create')
                <a href="{{ route('customers.create') }}" class="btn btn-success">New Customer</a>
            @endcan

            <div class="d-flex flex-column flex-md-row gap-3 w-100">
                <input type="text" id="liveSearch" class="form-control" value="{{ request('search') }}" placeholder="Search customer">

                <div class="mb-2 mb-md-0">
                    <strong>Total Results: </strong>
                    <span id="resultCount">{{ $customers->total() }}</span>
                </div>
            </div>
        </div>


        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle" id="customerTable">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Customer ID</th>
                        <th>Action</th>
                        <th>Phone</th>
                        <th>Image</th>
                        <th>Location</th>
                    </tr>
                </thead>
                <tbody id="customerBody">
                    @foreach ($customers as $customer)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><a href="{{ route('customers.emi_plans', $customer->id) }}">{{ $customer->customer_name }}
                            </td></a>
                            <td><a href="{{ route('report.print', $customer->id)}}">{{ $customer->customer_id }}</a></td>
                            <td>
                                @can('customer-edit')
                                    <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-warning btn-sm"><i
                                            class="fas fa-edit"></i></a>
                                @endcan

                                @can('customer-delete')
                                    <form action="{{ route('customers.destroy', $customer->id) }}" method="POST"
                                        style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Are you sure?')"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                @endcan

                            </td>
                            <td>
                                <a href="tel:{{ $customer->customer_phone }}">{{ $customer->customer_phone }}</a>
                                <br><a href="tel:{{ $customer->customer_phone2 }}">{{ $customer->customer_phone2 }}</a>
                            </td>
                            <td>
                                <a class="show-customer-modal" data-bs-toggle="modal" data-bs-target="#customerModal"
                                    data-name="{{ $customer->customer_name }}" data-id="{{ $customer->customer_id }}"
                                    data-phone="tel:{{ $customer->customer_phone }}"
                                    data-phone2="tel:{{ $customer->customer_phone2 }}"
                                    data-location="{{ $customer->location->name ?? 'N/A' }}"
                                    data-image="{{ asset($customer->customer_image) }}">
                                    <img src="{{ $customer->customer_image ? asset($customer->customer_image) : asset('image/profile.png') }}"
                                        class="img-fluid rounded-circle"
                                        style="height: 50px; width: 50px; object-fit: cover;" loading="lazy">


                                </a>
                            </td>
                            <td>
                                <a href="{{ route('customers.show', $customer->location->id ?? '#') }}">
                                    {{ $customer->location->name ?? 'N/A' }}
                                </a>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination Links -->
            <div class="d-flex justify-content-between mt-3">
                <div>
                    Showing {{ $customers->firstItem() }} to {{ $customers->lastItem() }} of {{ $customers->total() }}
                    results.
                </div>
                {{ $customers->links() }}
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="customerModal" tabindex="-1" aria-labelledby="customerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content p-4">
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
            let debounceTimer;

            // Load search result if there's a query in the URL
            const urlParams = new URLSearchParams(window.location.search);
            const searchQuery = urlParams.get('search');
            if (searchQuery) {
                $('#liveSearch').val(searchQuery);
                performSearch(searchQuery);
            }

            // Live search input listener
            $('#liveSearch').on('keyup', function() {
                const query = $(this).val();
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(function() {
                    // Update URL
                    const newUrl = new URL(window.location.href);
                    newUrl.searchParams.set('search', query);
                    window.history.replaceState(null, '', newUrl);

                    performSearch(query);
                }, 400);
            });

            function performSearch(query) {
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
            }

            // Modal population
            $('#customerTable').on('click', '.show-customer-modal', function() {
                const button = $(this);
                $('#modalCustomerName').text(button.data('name'));
                $('#modalCustomerID').text(button.data('id'));
                $('#modalCustomerPhone').text(button.data('phone'));
                $('#modalCustomerLocation').text(button.data('location'));
                $('#modalCustomerImage').attr('src', button.data('image') ||
                    '{{ asset('images/default.png') }}');
            });
        });
    </script>
@endpush
