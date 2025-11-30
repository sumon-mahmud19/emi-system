<!DOCTYPE html>
<html lang="bn">

<head>
    <meta charset="UTF-8">
    <title>EMI Invoice</title>
    <style>
        body {
            font-family: 'solaimanlipi', sans-serif;
            font-size: 14px;
        }

        h3 {
            margin: 0;
        }

        .header,
        .footer {
            text-align: center;
        }

        .customer-table,
        .installment-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        .customer-table td,
        .installment-table th,
        .installment-table td {
            padding: 8px;
        }

        .installment-table th,
        .installment-table td {
            border: 1px solid #000;
            padding: 8px;
        }

        .installment-table th {
            background-color: #f2f2f2;
        }

        .signature {
            margin-top: 60px;
            text-align: center;
        }

        .signature td {
            padding-top: 40px;
        }
    </style>
</head>

<body>

    <div class="header" style="margin-top: 10px;">
        <h2>রোমান ইলেকট্রিক এন্ড ফার্নিচার</h2>
        <div><b>লক্ষীপুরা রোড, বায়তুল ওমর জামে মসজিদ, (তিন রাস্তার মোড়), জয়দেবপুর, গাজীপুর।</b></div>
        <div>মোবাইল: ০১৮৭৫-৯৫৯২১৮</div>
    </div>
    <hr>

    <table class="customer-table">
        <tr>
            <td width="70%">
                <p><strong>ক্রেতার নাম:</strong> {{ $customer->customer_name }}</p>
                <p><strong>মোবাইল:</strong> {{ $customer->customer_phone }}</p>
                <p><strong>ঠিকানা:</strong> {{ $customer->location->name ?? 'N/A' }}</p>
            </td>
            <td id="image">
                <img src="{{ $customer->customer_image ? asset($customer->customer_image) : asset('image/profile.png') }}" width="120px" style="border-radius: 50px;" alt="Customer">
            </td>
        </tr>
    </table>

    <!-- Product Information Table -->
    <table class="installment-table" style="margin-top: 20px;">
        <thead>
            <tr>
                <th>পণ্যের নাম</th>
                <th>মডেল</th>
                <th>মোট দাম</th>
                <th>জমা</th>
                <th>কিস্তি মাস</th>

            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $product->product_name }}</td>
                <td>{{ $purchase->model->model_name ?? 'N/A' }}</td>
                <td>{{ number_format($purchase->net_price, 2) }} টাকা</td>
                <td>{{ number_format($purchase->down_price, 2) }} টাকা</td>
                <td>{{ $purchase->emi_plan }} মাস</td>
            </tr>
        </tbody>
    </table>

    <!-- Installment Table -->
    <table class="installment-table">
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
                    <td>{{ $installment->status === 'paid' ? 'পরিশোধিত' : 'বাকি' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Signatures -->
    <table class="signature" width="100%">
        <tr>
            <td>___________________________<br>ক্রেতার স্বাক্ষর</td>
            <td>___________________________<br>বিক্রেতার স্বাক্ষর</td>
        </tr>
    </table>

</body>

</html>
