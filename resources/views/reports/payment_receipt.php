<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>পেমেন্ট রসিদ</title>
    <style>
        body {
            font-family: 'solaimanlipi', sans-serif;
            font-size: 14px;
            margin: 10px;
        }
        h2, h4 {
            text-align: center;
            margin: 0;
        }
        .center {
            text-align: center;
        }
        .header {
            margin-bottom: 10px;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }
    </style>
</head>
<body>

    <h2>রোমান ইলেকট্রিক এন্ড ফার্নিচার</h2>
    <div class="header">
        দক্ষিণপাড়া রোড, বাঁশতলা ওভার ব্রিজ সংলগ্ন,<br>
        (তিন রাস্তার মোড়), মদনপুর, গাজীপুর।<br>
        মোবাইল: ০১৮৭৫-৯৫৯১৮৮
    </div>

    <table>
        <tr>
            <td><strong>ক্রেতার নাম:</strong> {{ $customer->customer_name }}</td>
            <td><strong>মোবাইল:</strong> {{ $customer->customer_phone }}</td>
            <td><strong>ঠিকানা:</strong> {{ $customer->location->name ?? 'N/A' }}</td>
        </tr>
    </table>

    @foreach($paidPurchases as $purchase)
        <h4>পণ্যের বিবরণ (ক্রয় আইডি: {{ $purchase->id }})</h4>
        <table>
            <thead>
                <tr>
                    <th>পণ্যের নাম</th>
                    <th>মডেল</th>
                    <th>মোট মূল্য</th>
                    <th>ডাউন পেমেন্ট</th>
                    <th>EMI প্ল্যান</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $purchase->product->product_name }}</td>
                    <td>{{ $purchase->model->model_name ?? 'N/A' }}</td>
                    <td>{{ number_format($purchase->total_price, 2) }} টাকা</td>
                    <td>{{ number_format($purchase->down_payment, 2) }} টাকা</td>
                    <td>{{ $purchase->emi_plan }} মাস</td>
                </tr>
            </tbody>
        </table>

        <h4>কিস্তির তালিকা</h4>
        <table>
            <thead>
                <tr>
                    <th>ক্রমিক</th>
                    <th>তারিখ</th>
                    <th>পরিমাণ</th>
                    <th>পরিশোধের অবস্থা</th>
                </tr>
            </thead>
            <tbody>
                @foreach($purchase->installments as $index => $installment)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($installment->due_date)->format('d-m-Y') }}</td>
                        <td>{{ number_format($installment->amount, 2) }} টাকা</td>
                        <td>
                            @if($installment->status === 'paid')
                                <span style="color:green">পরিশোধ</span>
                            @elseif($installment->status === 'partial')
                                <span style="color:orange">আংশিক</span>
                            @else
                                <span style="color:red">বাকি</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach

</body>
</html>
