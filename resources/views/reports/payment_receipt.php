<!DOCTYPE html>
<html lang="bn">

<head>
    <meta charset="UTF-8">
    <title>EMI Invoice</title>
    <style>
        body {
            font-family: 'solaimanlipi', sans-serif;
            font-size: 14px;
            line-height: 1.6;
        }

        .header,
        .footer {
            text-align: center;
        }

        .header h2 {
            margin-bottom: 5px;
        }

        .header div {
            margin-bottom: 2px;
        }

        hr {
            margin-top: 10px;
            margin-bottom: 20px;
        }

        .customer-table,
        .installment-table {
            width: 100%;
            border-collapse: collapse;
        }

        .customer-table td {
            padding: 6px 10px;
            vertical-align: top;
        }

        .installment-table th,
        .installment-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }

        .installment-table th {
            background-color: #f2f2f2;
        }

        .signature {
            margin-top: 60px;
            text-align: center;
            width: 100%;
        }

        .signature td {
            padding-top: 60px;
        }

        .bold {
            font-weight: bold;
        }

        .text-left {
            text-align: left;
        }

        img.profile {
            border-radius: 50%;
            border: 1px solid #ccc;
        }
    </style>
</head>

<body>

    <div class="header">
        <h2>রোমান ইলেকট্রিক এন্ড ফার্নিচার</h2>
        <div><b>লক্ষীপুরা রোড, বায়তুল ওমর জামে মসজিদ, (তিন রাস্তার মোড়), জয়দেবপুর, গাজীপুর।</b></div>
        <div>মোবাইল: ০১৮৭৫-৯৫৯২১৮</div>
    </div>

    <hr>

    <!-- Customer Info -->
    <table class="customer-table">
        <tr>
            <td width="70%">
                <p><span class="bold">ক্রেতার নাম:</span> {{ $customer->customer_name }}</p>
                <p><span class="bold">মোবাইল:</span> {{ $customer->customer_phone }}</p>
                <p><span class="bold">ঠিকানা:</span> {{ $customer->location->name ?? 'N/A' }}</p>
            </td>
            <td width="30%" align="right">
                <img class="profile" src="{{ $customer->customer_image ? asset($customer->customer_image) : asset('image/profile.png') }}" width="100" height="100" alt="Customer Image">
            </td>
        </tr>
    </table>

    <!-- Product Info -->
    <table class="installment-table" style="margin-top: 25px;">
        <thead>
            <tr>
                <th>পণ্যের নাম</th>
                <th>মডেল</th>
                <th>মোট মূল্য</th>
                <th>ডাউন পেমেন্ট</th>
                <th>কিস্তি মাস</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $product->product_name }}</td>
                <td>{{ $purchase->model->model_name ?? 'N/A' }}</td>
                <td>{{ number_format($purchase->total_price, 2) }} টাকা</td>
                <td>{{ number_format($purchase->down_payment, 2) }} টাকা</td>
                <td>{{ $purchase->emi_plan }} মাস</td>
            </tr>
        </tbody>
    </table>

    <!-- Installments Table -->
    <table class="installment-table" style="margin-top: 20px;">
        <thead>
            <tr>
                <th>ক্রমিক</th>
                <th>তারিখ</th>
                <th>পরিমাণ</th>
                <th>অবস্থা</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($installments as $index => $installment)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($installment->due_date)->format('d-m-Y') }}</td>
                    <td>{{ number_format($installment->amount, 2) }} টাকা</td>
                    <td>
                        @if($installment->status === 'paid')
                            পরিশোধিত
                        @elseif($installment->status === 'partial')
                            আংশিক
                        @else
                            বাকি
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Signatures -->
    <table class="signature">
        <tr>
            <td>___________________________<br>ক্রেতার স্বাক্ষর</td>
            <td>___________________________<br>বিক্রেতার স্বাক্ষর</td>
        </tr>
    </table>

</body>

</html>
