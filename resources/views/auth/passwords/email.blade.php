@extends('layouts.app')

@section('content')
<style>
    .reset-card {
        background-color: #fff;
        padding: 40px 30px;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }
    .reset-header {
        text-align: center;
        margin-bottom: 20px;
    }
    .reset-header h3 {
        font-weight: bold;
        color: #343a40;
    }
    .reset-subtext {
        font-size: 15px;
        color: #6c757d;
    }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-6">
            <div class="reset-card">
                <div class="reset-header">
                    <h3>🔑 Reset Password</h3>
                    <p class="reset-subtext">Enter your email address and we’ll send you a link to reset your password.</p>
                </div>

                @if (session('status'))
                    <div class="alert alert-success text-center">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">{{ __('Email Address') }}</label>
                        <input id="email" type="email"
                               class="form-control @error('email') is-invalid @enderror"
                               name="email" value="{{ old('email') }}"
                               required autocomplete="email" autofocus>

                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Send Password Reset Link') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
