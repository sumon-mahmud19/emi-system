@extends('layouts.app')

@section('content')
<div class="container">
    <h4>পেমেন্ট আপডেট করুন</h4>
    <form action="{{ route('payments.update', $payment->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="paid_at" class="form-label">তারিখ</label>
            <input type="date" name="paid_at" class="form-control" value="{{ \Carbon\Carbon::parse($payment->paid_at)->format('Y-m-d') }}" required>

        </div>

        <div class="mb-3">
            <label for="amount" class="form-label">জমার পরিমাণ (৳)</label>
            <input type="number" name="amount" class="form-control" value="{{ $payment->amount }}" required>
        </div>

        <button type="submit" class="btn btn-primary">আপডেট করুন</button>
    </form>
</div>
@endsection
