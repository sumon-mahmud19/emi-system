@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-4">নোটিশ লিস্ট</h2>

        {{-- Show Notices --}}
        <ul class="list-group mb-4">
            @foreach ($notices as $notice)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <form action="{{ route('notices.update', $notice->id) }}" method="POST" class="d-flex w-100 gap-2">
                        @csrf
                        @method('PUT')
                        <input type="text" name="name" value="{{ $notice->name }}" class="form-control" required>
                        <button type="submit" class="btn btn-primary btn-sm">Update</button>
                    </form>

                    <form action="{{ route('notices.destroy', $notice->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </li>
            @endforeach
        </ul>

        {{-- Add New Notice --}}
        <h4 class="mb-3">নতুন নোটিশ যোগ করুন</h4>
        <form action="{{ route('notices.store') }}" method="POST" class="d-flex gap-2 mb-4">
            @csrf
            <input type="text" name="name" class="form-control" placeholder="Enter notice" required>
            <button type="submit" class="btn btn-success">Save</button>
        </form>

        @error('name')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>
@endsection
