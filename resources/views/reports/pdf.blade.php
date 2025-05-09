<!DOCTYPE html>
<html lang="bn">

<head>
    <meta charset="UTF-8">
    <title>ইএমআই ইনভয়েস</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'solaimanlipi', sans-serif;
        }

        .invoice-wrapper {
            max-width: 800px;
            margin: auto;
            border: 1px solid #ddd;
            padding: 20px;
        }

        .invoice-header {
            text-align: center;
            border-bottom: 2px solid #000;
            margin-bottom: 20px;
        }

        .invoice-header h1 {
            margin: 0;
            font-size: 24px;
        }

        .customer-image img {
            width: 120px;
            height: 120px;
            border-radius: 10px;
            border: 2px solid #ccc;
            object-fit: cover;
        }

        .section-title {
            font-size: 18px;
            font-weight: bold;
            margin-top: 30px;
            border-bottom: 1px solid #aaa;
            padding-bottom: 5px;
        }

        .signature-row td {
            padding-top: 40px;
            text-align: center;
            border: none !important;
        }
    </style>
</head>

<body>

    <div class="invoice-wrapper bg-white">
        <div class="invoice-header">
            <h1>রোমান ইলেকট্রনিক্স এবং ফার্নিচার</h1>
            <b>01875959218</b>
            <p>ইএমআই ইনভয়েস</p>
        </div>

        <div class="row align-items-start mb-4">
            <div class="col-md-8">
                <p><strong>নাম:</strong> {{ $customer->customer_name }}</p>
                <p><strong>মোবাইল:</strong> {{ $customer->customer_phone }}</p>
                <p><strong>ঠিকানা:</strong> {{ $customer->location->name ?? 'N/A' }}</p>
                <p><strong>পণ্যের নাম:</strong> {{ $product->product_name }}</p>
                <p><strong>মোট কিস্তির পরিমাণ:</strong> {{ number_format($emiAmount * count($installments), 2) }} টাকা</p>
            </div>
            <div class="col-md-4 text-end">
                <div class="customer-image">
                    <img src="{{ $customer->customer_image }}" alt="Customer Image" width="40px">
                </div>
            </div>
        </div>

        <div>
            <div class="section-title">কিস্তির বিস্তারিত</div>
            <div class="table-responsive">
                <table class="table table-bordered table-sm text-center">
                    <thead class="table-light">
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
        </div>

        <footer class="mt-5">
            <table class="w-100">
                <tr class="signature-row">
                    <td colspan="2">ক্রেতার স্বাক্ষর</td>
                    <td colspan="2">বিক্রেতার স্বাক্ষর</td>
                </tr>
            </table>
        </footer>
    </div>

</body>

</html>
