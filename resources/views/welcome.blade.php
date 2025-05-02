@extends('layouts.app')

@section('content')
    {{-- Hero Section --}}
    <section class="hero-section">
        <div class="container">
            <h1 class="hero-title">রোমান ইলেকট্রনিক্স ও ফার্নিচার</h1>
            <p class="hero-subtitle">আমাদের EMI ম্যানেজমেন্ট সিস্টেমে আপনাকে স্বাগতম</p>
            <p class="mt-4">
                এখানে আপনি কাস্টমার, পারচেস, কিস্তি ও রিপোর্টসহ পুরো EMI সিস্টেম পরিচালনা করতে পারবেন।
            </p>
            <a href="{{ route('login') }}" class="btn btn-primary mt-3">সিস্টেমে প্রবেশ করুন</a>
        </div>
    </section>

    {{-- Features Section --}}
    <section class="py-5 bg-white">
        <div class="container">
            <h2 class="text-center mb-4">ফিচার সমূহ</h2>
            <div class="row text-center">
                <div class="col-md-3 mb-4">
                    <h5>কাস্টমার ম্যানেজমেন্ট</h5>
                    <p>নতুন কাস্টমার যুক্ত ও পরিচালনা করুন সহজে।</p>
                </div>
                <div class="col-md-3 mb-4">
                    <h5>পারচেস এন্ট্রি</h5>
                    <p>পণ্যের পারচেস রেকর্ড ও কিস্তি হিসাব রাখুন।</p>
                </div>
                <div class="col-md-3 mb-4">
                    <h5>কিস্তি কালেকশন</h5>
                    <p>মাসিক কিস্তির পরিশোধ ও ব্যালেন্স দেখুন।</p>
                </div>
                <div class="col-md-3 mb-4">
                    <h5>রিপোর্ট সিস্টেম</h5>
                    <p>বিস্তারিত মাসিক ও বার্ষিক রিপোর্ট পান।</p>
                </div>
            </div>
        </div>
    </section>
@endsection
