<!DOCTYPE html>
<html lang="bn">

<head>
    <meta charset="UTF-8">
    <title>ইএমআই ইনভয়েস</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'solaimanlipi', sans-serif;
            font-size: 14px;
            color: #333;
            background: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        .invoice-box {
            max-width: 800px;
            margin: 30px auto;
            padding: 30px;
            border: 1px solid #ddd;
            background: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .company-info {
            line-height: 1.6;
        }

        .logo {
            text-align: right;
        }

        .logo img {
            width: 100px;
            height: auto;
            object-fit: contain;
        }

        .invoice-title {
            text-align: center;
            margin: 30px 0 20px;
            font-size: 24px;
            font-weight: bold;
            border-top: 2px solid #000;
            padding-top: 10px;
        }

        .details-box {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .details-box .customer-info,
        .details-box .invoice-info {
            width: 48%;
        }

        .details-box p {
            margin: 5px 0;
        }

        .product-info {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #f9f9f9;
        }

        .summary {
            margin-top: 20px;
            text-align: right;
        }

        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ccc;
        }

        .signature {
            width: 45%;
            text-align: center;
        }

        .customer-photo {
            margin-top: 10px;
            text-align: right;
        }

        .customer-photo img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 2px solid #ccc;
            object-fit: cover;
        }
    </style>
</head>

<body>

    <div class="invoice-box">
        <div class="top-bar">
            <div class="company-info">
                <strong>রোমান ইলেকট্রনিক্স এবং ফার্নিচার</strong><br>
                ১২৩ মার্কেট রোড<br>
                ঢাকা, বাংলাদেশ
            </div>
            <div class="logo">
                <img src="{{ asset($customer->customer_image) }}" alt="Customer Image">
                
            </div>
        </div>

        <div class="invoice-title">ইএমআই ইনভয়েস</div>

        <div class="details-box">
            <div class="customer-info">
                <p><strong>ক্রেতার নাম:</strong> {{ $customer->customer_name }}</p>
                <p><strong>মোবাইল:</strong> {{ $customer->customer_phone }}</p>
                <p><strong>ঠিকানা:</strong> {{ $customer->location->name ?? 'N/A' }}</p>
                <p><strong>পণ্যের নাম:</strong> {{ $product->product_name }}</p>
            </div>
            <div class="invoice-info">
                <p><strong>ইনভয়েস নং:</strong> #{{ $invoiceNumber ?? '0001' }}</p>
                <p><strong>তারিখ:</strong> {{ now()->format('d-m-Y') }}</p>
                <p><strong>মোট কিস্তির পরিমাণ:</strong> {{ number_format($emiAmount * count($installments), 2) }} টাকা</p>
                <div class="customer-photo">
                    <img src="{{ asset($customer->customer_image) }}" alt="Customer Image">
                </div>
            </div>
        </div>

        <div class="product-info">
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
            </table>
        </div>

        <div class="signature-section">
            <div class="signature">
                ___________________________<br>
                ক্রেতার স্বাক্ষর
            </div>
            <div class="signature">
                ___________________________<br>
                বিক্রেতার স্বাক্ষর
            </div>
        </div>
    </div>

</body>
</html>
