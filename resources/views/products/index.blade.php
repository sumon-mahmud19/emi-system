@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow rounded-4 border-0">
        <div class="card-header bg-white rounded-top-4 d-flex justify-content-between align-items-center px-4 py-3">
            <h4 class="mb-0 text-dark fw-semibold">
                <i class="bi bi-box-seam me-2"></i> Product List
            </h4>

            @can('product-create')
                <a href="{{ route('products.create') }}" class="btn btn-success rounded-pill px-4">
                    <i class="bi bi-plus-circle me-1"></i> Add Product
                </a>
            @endcan
        </div>

        <div class="card-body bg-light-subtle rounded-bottom-4 p-4">
            @include('components.message')

            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle rounded shadow-sm">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 60px;">#</th>
                            <th>Product Name</th>
                            <th style="width: 180px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $product->product_name }}</td>
                                <td>
                                    @can('product-edit')
                                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-primary rounded-pill me-1">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </a>
                                    @endcan

                                    @can('product-delete')
                                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline"
                                              onsubmit="return confirmDelete(event)">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger rounded-pill">
                                                <i class="bi bi-trash3"></i> Delete
                                            </button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">No products found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Delete Confirmation Script --}}
<script>
    function confirmDelete(event) {
        if (!confirm('Are you sure you want to delete this product?')) {
            event.preventDefault();
        }
    }
</script>
@endsection
