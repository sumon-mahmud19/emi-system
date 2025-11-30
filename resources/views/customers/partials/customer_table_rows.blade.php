@foreach ($customers as $customer)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $customer->customer_name }}</td>

        <td><a href="{{ route('report.print', $customer->id) }}">{{ $customer->customer_id }}</a></td>
        <td>
            @can('customer-edit')
                <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-warning btn-sm">
                    <i class="fas fa-edit"></i>
                </a>
            @endcan

            @can('customer-delete')
                <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </form>
            @endcan

            <a href="{{ route('customers.emi_plans', $customer->id) }}" class="btn btn-sm btn-primary">
                <i class="fas fa-eye"></i>
            </a>
        </td>
        <td>
            <a href="tel:{{ $customer->customer_phone }}">{{ $customer->customer_phone }}</a>
        </td>
        <td>
            <a class="show-customer-modal" data-bs-toggle="modal" data-bs-target="#customerModal"
                data-name="{{ $customer->customer_name }}" data-id="{{ $customer->customer_id }}"
                data-phone="tel:{{ $customer->customer_phone }}"
                data-location="{{ $customer->location->name ?? 'N/A' }}"
                data-image="{{ asset($customer->customer_image ?? asset('image/profile.png')) }}">
                <img src="{{ $customer->customer_image ? asset($customer->customer_image) : asset('image/profile.png') }}"
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
