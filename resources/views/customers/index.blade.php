@extends('layouts.app')

@section('title', 'কাস্টমার তালিকা')

@section('content')
<div class="max-w-screen-xl mx-auto py-6 px-4">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">কাস্টমার তালিকা</h2>
        <div class="flex gap-3 w-full md:w-auto">
            @can('customer-create')
                <a href="{{ route('customers.create') }}" class="inline-block px-4 py-2 text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow">নতুন কাস্টমার</a>
            @endcan
            <input type="text" id="liveSearch" class="w-full md:w-64 px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="নাম / আইডি / ফোন দিয়ে খুঁজুন">
        </div>
    </div>

    <div class="mb-4 text-gray-600 dark:text-gray-300">
        <strong>ফলাফল:</strong> <span id="resultCount">{{ $customers->total() }}</span>
    </div>

    <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-300">
            <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-300">
                <tr>
                    <th class="px-4 py-3">#</th>
                    <th class="px-4 py-3">নাম</th>
                    <th class="px-4 py-3">আইডি</th>
                    <th class="px-4 py-3">ফোন</th>
                    <th class="px-4 py-3">ছবি</th>
                    <th class="px-4 py-3">ঠিকানা</th>
                    <th class="px-4 py-3">একশন</th>
                </tr>
            </thead>
            <tbody id="customerBody">
                @foreach ($customers as $customer)
                    <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-4 py-3">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3">{{ $customer->customer_name }}</td>
                        <td class="px-4 py-3">{{ $customer->customer_id }}</td>
                        <td class="px-4 py-3">
                            <a href="tel:{{ $customer->customer_phone }}" class="text-blue-600 dark:text-blue-400 hover:underline">{{ $customer->customer_phone }}</a>
                        </td>
                        <td class="px-4 py-3">
                            <button class="show-customer-modal" data-bs-toggle="modal" data-bs-target="#customerModal"
                                data-name="{{ $customer->customer_name }}"
                                data-id="{{ $customer->customer_id }}"
                                data-phone="tel:{{ $customer->customer_phone }}"
                                data-location="{{ $customer->location->name ?? 'N/A' }}"
                                data-image="{{ asset($customer->customer_image ?? 'images/default.png') }}">
                                <img src="{{ asset($customer->customer_image ?? 'images/default.png') }}"
                                     class="w-12 h-12 rounded-full object-cover border border-gray-300 shadow-sm" />
                            </button>
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('customers.show', $customer->location->id ?? '#') }}"
                               class="text-green-600 hover:underline dark:text-green-400">
                                {{ $customer->location->name ?? 'N/A' }}
                            </a>
                        </td>
                        <td class="px-4 py-3 flex flex-wrap gap-2">
                            @can('customer-edit')
                                <a href="{{ route('customers.edit', $customer->id) }}"
                                   class="px-3 py-1 text-sm bg-yellow-400 hover:bg-yellow-500 text-white rounded">Edit</a>
                            @endcan
                            @can('customer-delete')
                                <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1 text-sm bg-red-500 hover:bg-red-600 text-white rounded">Delete</button>
                                </form>
                            @endcan
                            <a href="{{ route('customers.emi_plans', $customer->id) }}"
                               class="px-3 py-1 text-sm bg-blue-500 hover:bg-blue-600 text-white rounded">EMI</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="p-4">
            {{ $customers->links() }}
        </div>
    </div>

    {{-- Customer Modal --}}
    <div class="modal fade" id="customerModal" tabindex="-1" aria-labelledby="customerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content p-4">
                <div class="modal-header border-b">
                    <h5 class="modal-title text-xl font-semibold">কাস্টমার ইনফরমেশন</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <h5 id="modalCustomerName" class="text-lg font-semibold mb-2"></h5>
                        <p><strong>ID:</strong> <span id="modalCustomerID"></span></p>
                        <p><strong>Phone:</strong> <span id="modalCustomerPhone"></span></p>
                        <p><strong>Location:</strong> <span id="modalCustomerLocation"></span></p>
                    </div>
                    <div class="flex justify-center">
                        <img id="modalCustomerImage" src="{{ asset('images/default.png') }}" alt="Customer Image"
                             class="max-h-72 w-auto rounded-lg shadow border border-gray-300 object-cover" />
                    </div>
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
        $('#liveSearch').on('keyup', function() {
            const query = $(this).val();
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(function() {
                $('#resultCount').text('লোড হচ্ছে...');
                $.ajax({
                    url: '{{ route('customers.index') }}',
                    method: 'GET',
                    data: { search: query },
                    success: function(response) {
                        $('#customerBody').html(response.html);
                        $('#resultCount').text(response.count + ' জন পাওয়া গেছে');
                    }
                });
            }, 500);
        });

        $('#customerTable').on('click', '.show-customer-modal', function() {
            const button = $(this);
            $('#modalCustomerName').text(button.data('name'));
            $('#modalCustomerID').text(button.data('id'));
            $('#modalCustomerPhone').text(button.data('phone'));
            $('#modalCustomerLocation').text(button.data('location'));
            $('#modalCustomerImage').attr('src', button.data('image'));
        });
    });
</script>
@endpush
