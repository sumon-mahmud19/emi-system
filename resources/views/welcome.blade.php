@extends('layouts.app')

@section('content')
<style>
    .hero-section {
        margin-top: 2.5rem;
        background-color: var(--bs-primary);
        color: #fff;
        padding: 100px 0;
        text-align: center;
    }
    .hero-section h1 {
        font-size: 2rem;
        font-weight: bold;
    }
    .hero-section p {
        font-size: 1.2rem;
        margin-bottom: 1rem;
    }
    .clock {
        font-size: 1.25rem;
        font-weight: 500;
        margin-top: 15px;
    }
    .feature-box {
        background: #ddd;
        border-radius: 12px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
        padding: 30px 20px;
        transition: all 0.3s ease-in-out;
        height: 100%;
    }
    .feature-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }
</style>

{{-- Hero Section --}}
<section class="hero-section">
    <div class="container">
        <h1>রোমান ইলেকট্রনিক্স ও ফার্নিচার</h1>
        <p>আমাদের EMI ম্যানেজমেন্ট সিস্টেমে আপনাকে স্বাগতম</p>
        <p>এখানে আপনি কাস্টমার, পারচেস, কিস্তি ও রিপোর্টসহ পুরো EMI সিস্টেম পরিচালনা করতে পারবেন।</p>
        <a href="{{ route('login') }}" class="btn btn-light btn-lg mt-3 px-4">সিস্টেমে প্রবেশ করুন</a>

        {{-- Live Clock --}}
        <div class="clock" id="bd-time">বাংলাদেশ সময়: Loading...</div>
    </div>
</section>

{{-- Features Section --}}
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center fw-bold mb-5">ফিচার সমূহ</h2>
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="feature-box text-center">
                    <h5>কাস্টমার ম্যানেজমেন্ট</h5>
                    <p>নতুন কাস্টমার যুক্ত ও পরিচালনা করুন সহজে।</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="feature-box text-center">
                    <h5>পারচেস এন্ট্রি</h5>
                    <p>পণ্যের পারচেস রেকর্ড ও কিস্তি হিসাব রাখুন।</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="feature-box text-center">
                    <h5>কিস্তি কালেকশন</h5>
                    <p>মাসিক কিস্তির পরিশোধ ও ব্যালেন্স দেখুন।</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="feature-box text-center">
                    <h5>রিপোর্ট সিস্টেম</h5>
                    <p>বিস্তারিত মাসিক ও বার্ষিক রিপোর্ট পান।</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Live Clock Script --}}
<script>
    function updateBDTime() {
        const bdTime = new Date().toLocaleString("en-US", {
            timeZone: "Asia/Dhaka",
            hour: 'numeric',
            minute: 'numeric',
            second: 'numeric',
            hour12: true
        });
        document.getElementById("bd-time").innerText = "বাংলাদেশ সময়: " + bdTime;
    }
    setInterval(updateBDTime, 1000);
    updateBDTime();
</script>
@endsection
