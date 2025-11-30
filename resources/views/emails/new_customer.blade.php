<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>নতুন গ্রাহক যোগ হয়েছে</title>
</head>
<body>
    <h2>নতুন গ্রাহক যোগ হয়েছে</h2>

    <p><strong>গ্রাহকের নাম:</strong> {{ $customer->customer_name }}</p>
    <p><strong>গ্রাহক আইডি:</strong> {{ $customer->customer_id }}</p>
    <p><strong>মোবাইল নম্বর:</strong> {{ $customer->customer_phone }}</p>
    <p><strong>ভাড়াদাতার নাম:</strong> {{ $customer->landlord_name }}</p>
    <p><strong>অবস্থান:</strong> {{ $customer->location->name ?? 'N/A' }}</p>

    @if ($customer->customer_image)
        <p><strong>ছবি:</strong></p>
        <img src="{{ asset($customer->customer_image) }}" alt="Customer Image" width="120">
    @endif
</body>
</html>
