<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>চালান</title>
    <style>
        @font-face {
            font-family: 'noto_bangla';
            src: url('{{ resource_path('fonts/NotoSansBengali-Regular.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        body {
            font-family: 'noto_bangla', sans-serif;
            line-height: 1.6;
            font-size: 15px;
        }

        .section-title {
            font-weight: bold;
            font-size: 18px;
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 6px 8px;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
        }

        .signatures {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }

        .signature-box {
            width: 40%;
            text-align: center;
        }

        .signature-line {
            margin-top: 40px;
            border-top: 1px solid #000;
            padding-top: 5px;
        }

        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #888;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>চালান - রোমান ইলেকট্রনিক্স এবং ফার্নিচার</h2>
    </div>

    <div class="section-title">গ্রাহক তথ্য</div>
    <table>
        <tr>
            <th>নাম</th>
            <td>{{ $customer->customer_name }}</td>
        </tr>
        <tr>
            <th>ফোন</th>
            <td>{{ $customer->customer_phone }}</td>
        </tr>
        <tr>
            <th>অবস্থান</th>
            <td>{{ $customer->location->name ?? 'প্রাপ্য নয়' }}</td>
        </tr>
    </table>

    <div class="section-title">পণ্য তথ্য</div>
    <table>
        <tr>
            <th>পণ্যের নাম</th>
            <td>{{ $product->name }}</td>
        </tr>
        <tr>
            <th>মডেল</th>
            <td>{{ $purchase->model->name ?? 'প্রাপ্য নয়' }}</td>
        </tr>
        <tr>
            <th>মোট মূল্য</th>
            <td>{{ number_format($purchase->total_price) }} টাকা</td>
        </tr>
        <tr>
            <th>বিক্রয় মূল্য</th>
            <td>{{ number_format($purchase->sales_price) }} টাকা</td>
        </tr>
        <tr>
            <th>অগ্রিম পেমেন্ট</th>
            <td>{{ number_format($purchase->down_payment) }} টাকা</td>
        </tr>
        <tr>
            <th>ইএমআই সময়কাল</th>
            <td>{{ $purchase->emi_plan }} মাস</td>
        </tr>
        <tr>
            <th>মাসিক কিস্তি</th>
            <td>{{ number_format($emiAmount) }} টাকা</td>
        </tr>
    </table>

    <div class="section-title">ইএমআই সময়সূচি</div>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>বিলম্বিত তারিখ</th>
                <th>পরিমাণ</th>
                <th>অবস্থা</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($installments as $index => $inst)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($inst->due_date)->format('F Y') }}</td>
                    <td>{{ number_format($inst->amount) }} টাকা</td>
                    <td>{{ $inst->status === 'paid' ? 'পেমেন্ট হয়েছে' : 'বকেয়া' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="signatures">
        <div class="signature-box">
            <div class="signature-line">গ্রাহক স্বাক্ষর</div>
        </div>
        <div class="signature-box">
            <div class="signature-line">বিক্রেতার স্বাক্ষর</div>
        </div>
    </div>

    <div class="footer">
        &copy; {{ date('Y') }} রোমান ইলেকট্রনিক্স এবং ফার্নিচার। সকল অধিকার সংরক্ষিত।
    </div>

</body>
</html>
