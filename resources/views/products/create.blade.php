@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow rounded-4 border-0">
        <div class="card-header bg-white rounded-top-4 px-4 py-3 d-flex justify-content-between align-items-center">
            <h4 class="mb-0 text-dark fw-semibold">
                <i class="bi bi-plus-circle me-2"></i> Create Product
            </h4>
            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill">
                <i class="bi bi-arrow-left"></i> Back to List
            </a>
        </div>

        <div class="card-body bg-light-subtle rounded-bottom-4 p-4">
            @if ($errors->any())
                <div class="alert alert-danger rounded-3">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('products.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="product_name" class="form-label fw-semibold">Product Name</label>
                    <input type="text" name="product_name" id="product_name"
                           class="form-control rounded-3 shadow-sm"
                           value="{{ old('product_name') }}" required>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary rounded-pill px-4">
                        <i class="bi bi-check-circle me-1"></i> Save
                    </button>
                    <a href="{{ route('products.index') }}" class="btn btn-secondary rounded-pill px-4">
                        <i class="bi bi-x-circle me-1"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
