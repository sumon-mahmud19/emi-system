@foreach ($customers as $customer)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $customer->customer_name }}</td>
        <td>{{ $customer->customer_id }}</td>
        <td><a href="tel:{{ $customer->customer_phone }}">{{ $customer->customer_phone }}</a></td>
        <td>
            <a class="show-customer-modal" data-bs-toggle="modal" data-bs-target="#customerModal"
                data-name="{{ $customer->customer_name }}"
                data-id="{{ $customer->customer_id }}"
                data-phone="tel:{{ $customer->customer_phone }}"
                data-location="{{ $customer->location->name ?? 'N/A' }}"
                data-image="{{ asset($customer->customer_image ?? 'images/default.png') }}">
                <img src="{{ asset($customer->customer_image ?? 'images/default.png') }}"
                    class="img-fluid rounded-circle"
                    style="height: 50px; width: 50px; object-fit: cover;">
            </a>
        </td>
        <td>{{ $customer->location->name ?? 'N/A' }}</td>
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
