<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>ইএমআই ইনভয়েস</title>
    <style>
        body {
            font-family: 'solaimanlipi';
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 6px;
            text-align: left;
        }
        .signature-row td {
            border: none;
            padding-top: 40px;
        }
    </style>
</head>
<body>

    <h1>রোমান ইলেকট্রনিক্স এবং আসবাবপত্র</h1>

    <p><strong>ক্রেতার নাম:</strong> {{ $customer->customer_name }}</p>
    <p><strong>মোবাইল:</strong> {{ $customer->customer_phone }}</p>
    <p><strong>পণ্যের নাম:</strong> {{ $product->product_name }}</p>
    <p><strong>মোট কিস্তির পরিমাণ:</strong> {{ number_format($emiAmount * count($installments), 2) }} টাকা</p>

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
                <td>ক্রেতার স্বাক্ষর</td>
                <td></td>
                <td></td>
                <td>বিক্রেতার স্বাক্ষর</td>
            </tr>
        </tfoot>
    </table>

</body>
</html>
