<!DOCTYPE html>
<html lang="bn">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>ইএমআই ইনভয়েস - রোমান ইলেকট্রনিক্স</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }

        .invoice-area {
            padding: 40px 20px;
            background-color: #f7f7f7;
        }

        .invoice-wrapper {
            background-color: #fff;
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #ddd;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }

        .invoice-header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .invoice-title {
            font-size: 24px;
            font-weight: 700;
        }

        .invoice-details-flex {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
            margin-bottom: 30px;
        }

        .invoice-single-details {
            width: 48%;
        }

        .details-list {
            list-style: none;
            padding: 0;
            margin: 0;
            line-height: 1.8;
        }

        .details-list .list {
            font-size: 15px;
        }

        .customer-image {
            text-align: center;
        }

        .customer-image img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 2px solid #ccc;
            object-fit: cover;
        }

        .table-title {
            font-size: 18px;
            margin: 20px 0 10px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }

        .custom--table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .custom--table th, .custom--table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
            font-size: 14px;
        }

        .custom--table th {
            background-color: #f2f2f2;
        }

        .data-span {
            font-weight: 600;
        }

        footer {
            text-align: center;
            margin-top: 30px;
            font-size: 14px;
            color: #777;
        }
    </style>
</head>

<body>

<!-- Invoice area Starts -->
<div class="invoice-area">
    <div class="invoice-wrapper">
        <div class="invoice-header">
            <h1 class="invoice-title">রোমান ইলেকট্রনিক্স এবং ফার্নিচার</h1>
            <p>মোবাইল: 01875959218</p>
            <p>ইএমআই ইনভয়েস</p>
        </div>

        <div class="invoice-details">
            <div class="invoice-details-flex">
                <div class="invoice-single-details">
                    <h2 class="invoice-details-title">ক্রেতার তথ্য:</h2>
                    <ul class="details-list">
                        <li class="list"><strong>নাম:</strong> {{ $customer->customer_name }}</li>
                        <li class="list"><strong>মোবাইল:</strong> {{ $customer->customer_phone }}</li>
                        <li class="list"><strong>ঠিকানা:</strong> {{ $customer->location->name ?? 'N/A' }}</li>
                        <li class="list"><strong>পণ্যের নাম:</strong> {{ $product->product_name }}</li>
                        <li class="list"><strong>মোট কিস্তি:</strong> {{ number_format($emiAmount * count($installments), 2) }} টাকা</li>
                    </ul>
                </div>
                <div class="invoice-single-details customer-image">
                    <img src="{{ $customer->customer_image }}" alt="Customer Image">
                </div>
            </div>
        </div>

        <div class="item-description">
            <h5 class="table-title">কিস্তির তালিকা</h5>
            <table class="custom--table">
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
            <p>Copyright © {{ now()->year }} - রোমান ইলেকট্রনিক্স</p>
        </footer>
    </div>
</div>
<!-- Invoice area end -->

</body>

</html>
