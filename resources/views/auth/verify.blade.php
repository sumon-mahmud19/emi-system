@extends('layouts.app')

@section('content')
<style>
    .verify-card {
        background-color: #ffffff;
        padding: 40px 30px;
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }
    .verify-header h3 {
        font-weight: 700;
        margin-bottom: 20px;
        color: #343a40;
    }
    .verify-message {
        font-size: 16px;
        color: #6c757d;
    }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-6">
            <div class="verify-card">
                <div class="verify-header text-center">
                    <h3>📧 Verify Your Email Address</h3>
                </div>

                @if (session('resent'))
                    <div class="alert alert-success text-center" role="alert">
                        ✅ A fresh verification link has been sent to your email.
                    </div>
                @endif

                <p class="verify-message text-center">
                    Before proceeding, please check your inbox for a verification link.
                </p>
                <p class="verify-message text-center mb-4">
                    If you didn't receive the email,
                </p>

                <form class="text-center" method="POST" action="{{ route('verification.resend') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-primary px-4">
                        🔄 Click here to request another
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
