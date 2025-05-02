@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">লোকেশন এডিট করুন</div>
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('locations.update', $location->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">লোকেশন নাম</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $location->name) }}" required>
            </div>
            <button type="submit" class="btn btn-primary">আপডেট করুন</button>
            <a href="{{ route('locations.index') }}" class="btn btn-secondary">বাতিল</a>
        </form>
    </div>
</div>
@endsection
