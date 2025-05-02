@extends('layouts.app')

@section('content')
<style>
    body {
        background: linear-gradient(to right, #6610f2, #6f42c1);
    }
    .register-card {
        background: #ffffff;
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        padding: 40px 30px;
    }
    .register-header {
        text-align: center;
        margin-bottom: 30px;
    }
    .register-header h3 {
        font-weight: 700;
        color: #343a40;
    }
    .form-label {
        font-weight: 600;
    }
    .btn-primary {
        padding: 10px 25px;
        font-weight: 600;
        border-radius: 10px;
    }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-6">
            <div class="register-card">
                <div class="register-header">
                    <h3>📝 Create Your Account</h3>
                    <p class="text-muted">Register to use the EMI Management System</p>
                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">{{ __('Name') }}</label>
                        <input id="name" type="text"
                            class="form-control @error('name') is-invalid @enderror"
                            name="name" value="{{ old('name') }}" required autofocus>

                        @error('name')
                            <span class="invalid-feedback d-block">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">{{ __('Email Address') }}</label>
                        <input id="email" type="email"
                            class="form-control @error('email') is-invalid @enderror"
                            name="email" value="{{ old('email') }}" required>

                        @error('email')
                            <span class="invalid-feedback d-block">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">{{ __('Password') }}</label>
                        <input id="password" type="password"
                            class="form-control @error('password') is-invalid @enderror"
                            name="password" required>

                        @error('password')
                            <span class="invalid-feedback d-block">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password-confirm" class="form-label">{{ __('Confirm Password') }}</label>
                        <input id="password-confirm" type="password" class="form-control"
                            name="password_confirmation" required>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Register') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
