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
                    <h3>🔑 Reset Your Password</h3>
                    <p class="reset-subtext">Enter your new password to reset and regain access.</p>
                </div>

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="mb-3">
                        <label for="email" class="form-label">{{ __('Email Address') }}</label>
                        <input id="email" type="email"
                               class="form-control @error('email') is-invalid @enderror"
                               name="email" value="{{ $email ?? old('email') }}"
                               required autocomplete="email" autofocus>

                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">{{ __('New Password') }}</label>
                        <input id="password" type="password"
                               class="form-control @error('password') is-invalid @enderror"
                               name="password" required autocomplete="new-password">

                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password-confirm" class="form-label">{{ __('Confirm New Password') }}</label>
                        <input id="password-confirm" type="password" class="form-control"
                               name="password_confirmation" required autocomplete="new-password">
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Reset Password') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
