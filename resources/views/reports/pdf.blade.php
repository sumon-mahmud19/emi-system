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
            line-height: 1.6;
            color: #333;
            background: #f9f9f9;
        }

        .invoice-wrapper {
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
            background: #fff;
            border: 1px solid #ddd;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }

        .invoice-header {
            text-align: center;
            border-bottom: 2px solid #000;
            margin-bottom: 20px;
        }

        .invoice-header h1 {
            margin: 0;
            font-size: 26px;
            font-weight: bold;
        }

        .customer-section {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .customer-image {
            order: 2; /* Move image to right */
            flex: 0 0 130px;
            text-align: center;
        }

        .customer-image img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            overflow: hidden;
        }

        .customer-details {
            order: 1; /* Keep details on left */
            flex: 1;
            min-width: 250px;
        }

        .customer-details p {
            margin: 5px 0;
            font-size: 15px;
        }

        .section-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
            border-bottom: 1px solid #aaa;
            padding-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            border: 1px solid #aaa;
            padding: 8px;
            text-align: center;
        }

        th {
            background: #f5f5f5;
        }

        .signature-row td {
            border: none;
            padding-top: 40px;
            text-align: center;
        }

        footer {
            margin-top: 30px;
        }
    </style>
</head>

<body>

    <div class="invoice-wrapper">
        <div class="invoice-header">
            <h1>রোমান ইলেকট্রনিক্স এবং ফার্নিচার</h1>
            <p>ইএমআই ইনভয়েস</p>
        </div>

        <div class="customer-section">
            <div class="customer-details">
                <p><strong>নাম:</strong> {{ $customer->customer_name }}</p>
                <p><strong>মোবাইল:</strong> {{ $customer->customer_phone }}</p>
                <p><strong>ঠিকানা:</strong> {{ $customer->location->name ?? 'N/A' }}</p>
                <p><strong>পণ্যের নাম:</strong> {{ $product->product_name }}</p>
                <p><strong>মোট কিস্তির পরিমাণ:</strong> {{ number_format($emiAmount * count($installments), 2) }} টাকা</p>
            </div>
            <div class="customer-image">
                <img src="{{ asset($customer->customer_image) }}" alt="Customer Image">
            </div>
        </div>

        <div>
            <div class="section-title">কিস্তির বিস্তারিত</div>
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

        <footer>
            <table style="width:100%; margin-top: 40px;">
                <tr class="signature-row">
                    <td colspan="2">ক্রেতার স্বাক্ষর</td>
                    <td colspan="2">বিক্রেতার স্বাক্ষর</td>
                </tr>
            </table>
        </footer>
    </div>

</body>
</html>