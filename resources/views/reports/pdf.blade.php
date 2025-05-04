<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>ইনভয়েস</title>

    <style>
        @font-face {
            font-family: 'notosansbengali';
            font-style: normal;
            font-weight: normal;
            src: url('{{ public_path('fonts/NotoSansBengali-Regular.ttf') }}') format('truetype');
        }

        body {
            font-family: 'notosansbengali', sans-serif;
            font-size: 16px;
            line-height: 26px;
            direction: ltr;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 8px;
            border: 1px solid #ccc;
            text-align: left;
        }

        .section-title {
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
        }

        .invoice-header {
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .footer-signature {
            display: flex;
            justify-content: space-between;
            margin-top: 50px;
        }

        .signature-box {
            width: 45%;
            text-align: center;
        }

        .signature-line {
            border-top: 1px solid #000;
            margin-top: 60px;
            padding-top: 5px;
        }
    </style>
</head>
<body>

    <div class="invoice-header">
        ইনভয়েস - Roman Electronics & Furnitures
    </div>

    <div class="section-title">কাস্টমার তথ্য</div>
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
            <th>লোকেশন</th>
            <td>{{ $customer->location->name ?? 'N/A' }}</td>
        </tr>
    </table>

    <div class="section-title">পণ্যের তথ্য</div>
    <table>
        <tr>
            <th>পণ্যের নাম</th>
            <td>{{ $product->name }}</td>
        </tr>
        <tr>
            <th>মডেল</th>
            <td>{{ $purchase->model->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>মূল্য</th>
            <td>{{ number_format($purchase->total_price) }} টাকা</td>
        </tr>
        <tr>
            <th>বিক্রয় মূল্য</th>
            <td>{{ number_format($purchase->sales_price) }} টাকা</td>
        </tr>
        <tr>
            <th>ডাউন পেমেন্ট</th>
            <td>{{ number_format($purchase->down_payment) }} টাকা</td>
        </tr>
        <tr>
            <th>EMI সংখ্যা</th>
            <td>{{ $purchase->emi_plan }} মাস</td>
        </tr>
        <tr>
            <th>প্রতি মাসে কিস্তি</th>
            <td>{{ number_format($emiAmount) }} টাকা</td>
        </tr>
    </table>

    <div class="section-title">EMI কিস্তির তালিকা</div>
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
            @foreach ($installments as $index => $inst)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($inst->due_date)->format('F Y') }}</td>
                <td>{{ number_format($inst->amount) }} টাকা</td>
                <td>
                    @if ($inst->status == 'paid')
                        পরিশোধিত
                    @else
                        বকেয়া
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer-signature">
        <div class="signature-box">
            <div class="signature-line">ত্রেতা স্বাক্ষর</div>
        </div>
        <div class="signature-box">
            <div class="signature-line">বিত্রেতার স্বাক্ষর</div>
        </div>
    </div>

    <div style="text-align:center; margin-top: 30px;">
        <strong>সর্বস্বত্ব সংরক্ষিত © {{ date('Y') }} - Roman Electronics & Furnitures</strong>
    </div>

</body>
</html>
