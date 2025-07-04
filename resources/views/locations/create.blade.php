@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow rounded-4 border-0">
        <div class="card-header bg-white border-bottom rounded-top-4 px-4 py-3">
            <h4 class="mb-0 text-success fw-semibold">
                <i class="bi bi-plus-circle-fill me-2"></i> নতুন লোকেশন যুক্ত করুন
            </h4>
        </div>

        <div class="card-body bg-light-subtle rounded-bottom-4 p-4">
            {{-- Error Alert --}}
            @if ($errors->any())
                <div class="alert alert-danger shadow-sm rounded-3">
                    <h6 class="fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i>ত্রুটি</h6>
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li class="small">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Add Location Form --}}
            <form action="{{ route('locations.store') }}" method="POST" class="needs-validation" novalidate>
                @csrf
                <div class="mb-4">
                    <label for="name" class="form-label fw-semibold">লোকেশন নাম</label>
                    <input type="text" name="name" id="name"
                        class="form-control rounded-pill px-4 py-2 @error('name') is-invalid @enderror"
                        value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success rounded-pill px-4">
                        <i class="bi bi-check-lg me-1"></i> সেভ করুন
                    </button>

                    <a href="{{ route('locations.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="bi bi-x-lg me-1"></i> বাতিল
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
