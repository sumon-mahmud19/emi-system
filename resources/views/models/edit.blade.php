@extends('layouts.app')

@section('content')
<div class="container">
    <h3>মডেল এডিট করুন</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('models.update', $model->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="product_id">পণ্য নির্বাচন করুন</label>
            <select name="product_id" class="form-select" required>
                <option value="">নির্বাচন করুন...</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" {{ $model->product_id == $product->id ? 'selected' : '' }}>
                        {{ $product->product_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="model_name">মডেল নাম</label>
            <input type="text" name="model_name" class="form-control" value="{{ $model->model_name }}" required>
        </div>

        <button class="btn btn-primary">আপডেট করুন</button>
    </form>
</div>
@endsection
