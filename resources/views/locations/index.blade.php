@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow rounded-4 border-0">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center rounded-top-4 px-4 py-3">
            <h4 class="mb-0 text-primary fw-semibold">
                <i class="bi bi-geo-alt-fill me-2"></i> সব লোকেশন
            </h4>
            @can('location-create')
                <a href="{{ route('locations.create') }}" class="btn btn-success rounded-pill px-4">
                    <i class="bi bi-plus-lg me-1"></i> নতুন লোকেশন
                </a>
            @endcan
        </div>

        <div class="card-body bg-light-subtle rounded-bottom-4 p-4">
            @include('components.message')

            <div class="table-responsive">
                <table class="table table-hover align-middle text-center shadow-sm bg-white rounded-3">
                    <thead class="table-light">
                        <tr class="fw-semibold text-secondary">
                            <th>আইডি</th>
                            <th>লোকেশন নাম</th>
                            <th>অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($locations as $location)
                            <tr>
                                <td>{{ $location->id }}</td>
                                <td class="text-capitalize">{{ $location->name }}</td>
                                <td>
                                    @can('location-edit')
                                        <a href="{{ route('locations.edit', $location->id) }}" class="btn btn-sm btn-outline-primary me-1 rounded-pill px-3">
                                            <i class="bi bi-pencil-square"></i> এডিট
                                        </a>
                                    @endcan

                                    @can('location-delete')
                                        <form action="{{ route('locations.destroy', $location->id) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('আপনি কি নিশ্চিতভাবে মুছতে চান?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                                <i class="bi bi-trash3"></i> ডিলিট
                                            </button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-muted py-4">😕 কোনো লোকেশন পাওয়া যায়নি।</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
