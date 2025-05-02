@foreach ($customers as $customer)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $customer->customer_name }}</td>
        <td>{{ $customer->customer_id }}</td>
        <td>{{ $customer->customer_phone }}</td>
        <td>
            @if ($customer->customer_image)
                <img src="{{ asset($customer->customer_image) }}" alt="Customer Image" width="80" height="80"
                    class="img-thumbnail" style="cursor:pointer" data-bs-toggle="modal" data-bs-target="#customerModal"
                    data-name="{{ $customer->customer_name }}" data-id="{{ $customer->customer_id }}"
                    data-phone="{{ $customer->customer_phone }}"
                    data-location="{{ optional($customer->location)->name }}"
                    data-image="{{ asset($customer->customer_image) }}">
            @endif
        </td>
        <td>
            @if ($customer->location)
                <a href="{{ route('customers.show', $customer->location->id) }}">
                    {{ $customer->location->name }}
                </a>
            @else
                <span class="text-muted">No location</span>
            @endif
        </td>
        <td>
            @can('customer-edit')
                <a href="{{ route('customers.edit', $customer) }}" class="btn btn-sm btn-warning mb-1">Edit</a>
            @endcan

            @can('customer-delete')
                <form action="{{ route('customers.destroy', $customer) }}" method="POST" style="display:inline;">
                    @csrf @method('DELETE')
                    <button onclick="return confirm('Delete this customer?')"
                        class="btn btn-sm btn-danger mb-1">Delete</button>
                </form>
            @endcan

            <a href="{{ route('customers.emi_plans', $customer->id) }}" class="btn btn-sm btn-primary">EMI Details</a>
        </td>
    </tr>
@endforeach
