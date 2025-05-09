@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Customers</h2>

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">
        @can('customer-create')
            <a href="{{ route('customers.create') }}" class="btn btn-success">
                <i class="bi bi-person-plus-fill"></i> New Customer
            </a>
        @endcan

        <div class="d-flex flex-column flex-md-row gap-2 w-100 justify-content-md-end">
            <input type="text" id="liveSearch" class="form-control" placeholder="Search customer (e.g. shakil-fan-pl)">
            <div>
                <strong>Total:</strong> <span id="resultCount">{{ $customers->total() }}</span>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Customer ID</th>
                    <th>Actions</th>
                    <th>Phone</th>
                    <th>Image</th>
                    <th>Location</th>
                </tr>
            </thead>
            <tbody id="customerBody">
                @foreach ($customers as $customer)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $customer->customer_name }}</td>
                        <td>{{ $customer->customer_id }}</td>
                        <td class="d-flex gap-1">
                            @can('customer-edit')
                                <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                            @endcan

                            @can('customer-delete')
                                <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            @endcan

                            <a href="{{ route('customers.emi_plans', $customer->id) }}" class="btn btn-sm btn-primary" title="EMI Details">
                                <i class="bi bi-card-list"></i>
                            </a>
                        </td>
                        <td>
                            <a href="tel:{{ $customer->customer_phone }}">{{ $customer->customer_phone }}</a>
                        </td>
                        <td>
                            <a class="show-customer-modal" data-bs-toggle="modal" data-bs-target="#customerModal"
                               data-name="{{ $customer->customer_name }}"
                               data-id="{{ $customer->customer_id }}"
                               data-phone="tel:{{ $customer->customer_phone }}"
                               data-location="{{ $customer->location->name ?? 'N/A' }}"
                               data-image="{{ asset($customer->customer_image ?? 'images/default.png') }}">
                                <img src="{{ asset($customer->customer_image ?? 'images/default.png') }}"
                                     class="img-fluid rounded-circle" style="height: 50px; width: 50px; object-fit: cover;">
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

        <div class="d-flex justify-content-between mt-3">
            <div>
                Showing {{ $customers->firstItem() }} to {{ $customers->lastItem() }} of {{ $customers->total() }} results.
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
                    <h5 id="modalCustomerName" class="mb-3"></h5>
                    <p><strong>Customer ID:</strong> <span id="modalCustomerID"></span></p>
                    <p><strong>Phone:</strong> <span id="modalCustomerPhone"></span></p>
                    <p><strong>Location:</strong> <span id="modalCustomerLocation"></span></p>
                </div>
                <div class="col-md-6 text-center d-flex align-items-center justify-content-center">
                    <img id="modalCustomerImage" src="{{ asset('images/default.png') }}" class="img-fluid rounded shadow" style="max-height: 350px;">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function () {
        let debounceTimer;

        $('#liveSearch').on('keyup', function () {
            const query = $(this).val();
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                $('#resultCount').text('Loading...');
                $.get('{{ route('customers.index') }}', { search: query }, function (response) {
                    $('#customerBody').html(response.html);
                    $('#resultCount').text(response.count + ' results');
                });
            }, 500);
        });

        $('#customerTable').on('click', '.show-customer-modal', function () {
            const button = $(this);
            $('#modalCustomerName').text(button.data('name'));
            $('#modalCustomerID').text(button.data('id'));
            $('#modalCustomerPhone').text(button.data('phone'));
            $('#modalCustomerLocation').text(button.data('location'));
            $('#modalCustomerImage').attr('src', button.data('image') || '{{ asset("images/default.png") }}');
        });
    });
</script>
@endpush
