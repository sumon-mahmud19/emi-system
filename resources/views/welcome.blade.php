@extends('layouts.app')

@section('content')
<style>
    body {
        background: linear-gradient(to right, #0062E6, #33AEFF);
        min-height: 100vh;
    }

    .login-container {
        margin-top: 5%;
        margin-bottom: 5%;
    }

    .login-form {
        padding: 30px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 0 20px rgba(0,0,0,0.2);
    }

    .login-form h3 {
        text-align: center;
        margin-bottom: 30px;
        font-weight: bold;
        color: #333;
    }

    .navbar {
        background-color: #003d82;
    }
</style>

<div class="container login-container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="login-form">
                <h3>Login</h3>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-group mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input id="email" type="email"
                               class="form-control @error('email') is-invalid @enderror"
                               name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                        @error('email')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input id="password" type="password"
                               class="form-control @error('password') is-invalid @enderror"
                               name="password" required autocomplete="current-password">

                        @error('password')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember"
                               {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">
                            Remember Me
                        </label>
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary w-100">
                            Login
                        </button>
                    </div>

                    @if (Route::has('password.request'))
                        <div class="text-center">
                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                Forgot Your Password?
                            </a>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
