{{-- resources/views/product_models/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Add Product Model</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('models.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="product_id">Select Product</label>
            <select name="product_id" class="form-select" required>
                <option value="">Choose...</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="model_name">Model Name</label>
            <input type="text" name="model_name" class="form-control" required>
        </div>

        <button class="btn btn-primary">Add Model</button>
    </form>
</div>
@endsection
