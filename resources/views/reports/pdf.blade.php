<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>ইএমআই ইনভয়েস</title>
    <style>
        body {
            font-family: 'solaimanlipi', sans-serif;
            font-size: 14px;
            line-height: 1.6;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .customer-info {
            width: 65%;
        }

        .customer-info h2 {
            margin: 0;
            font-size: 20px;
        }

        .customer-info p {
            margin: 4px 0;
        }

        .customer-image {
            width: 30%;
            text-align: right;
        }

        .customer-image img {
            max-width: 100px;
            max-height: 100px;
            border-radius: 8px;
            border: 1px solid #aaa;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }

        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }

        .signature-row td {
            border: none;
            padding-top: 40px;
            text-align: center;
        }

        .title {
            text-align: center;
            font-size: 22px;
            margin-bottom: 10px;
        }

        .summary {
            margin-top: 10px;
        }
    </style>
</head>
<body>

    <div class="title">রোমান ইলেকট্রনিক্স এবং আসবাবপত্র</div>

    <div class="header">
        <div class="customer-info">
            <h2>ক্রেতার তথ্য</h2>
            <p><strong>নাম:</strong> {{ $customer->customer_name }}</p>
            <p><strong>মোবাইল:</strong> {{ $customer->customer_phone }}</p>
            <p><strong>ঠিকানা:</strong> {{ $customer->address ?? 'N/A' }}</p>
            <p><strong>পণ্যের নাম:</strong> {{ $product->product_name }}</p>
            <p><strong>মোট কিস্তির পরিমাণ:</strong> {{ number_format($emiAmount * count($installments), 2) }} টাকা</p>
        </div>

        <div class="customer-image">
            @if($customer->customer_image)
                <img src="{{ public_path('image/customers/' . $customer->customer_image) }}" alt="Customer Image">
            @else
                <img src="{{ public_path('image/customers/default.png') }}" alt="No Image">
            @endif
        </div>
    </div>

    <table>
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
                    <td>{{ number_format($installment->amount, 2) }}</td>
                    <td>{{ $installment->status === 'paid' ? 'পরিশোধিত' : 'বাকি' }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="signature-row">
                <td colspan="2">ক্রেতার স্বাক্ষর</td>
                <td colspan="2">বিক্রেতার স্বাক্ষর</td>
            </tr>
        </tfoot>
    </table>

</body>
</html>
