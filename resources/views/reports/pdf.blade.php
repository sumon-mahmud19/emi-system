<!doctype html>
<html lang="bn">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EMI Invoice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * {
            font-family: 'solaimanlipi', sans-serif;
        }

        .rounded-img {
            border-radius: 50%;
            width: 120px;
            height: 120px;
            object-fit: cover;
            border: 2px solid #ddd;
        }

        .signature {
            text-align: center;
            padding-top: 30px;
            font-size: 16px;
            font-weight: bold;
        }

        th, td {
            text-align: center;
            vertical-align: middle;
        }

        .invoice-header {
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .customer-image-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .table th, .table td {
            border: 1px solid #000 !important;
        }
    </style>
</head>

<body>
    <div class="container py-4">

        <!-- Header -->
        <div class="text-center invoice-header">
            <h3 class="fw-bold">রোমান ইলেকট্রিক এন্ড ফার্নিচার</h3>
            <div>লক্ষীপুরা রোড, বায়তুল ওমর জামে মসজিদ, (তিন রাস্তার মোড়), জয়দেবপুর, গাজীপুর।</div>
            <div>মোবাইল: ০১৮৭৫-৯৫৯২১৮</div>
        </div>

        <!-- Customer Info & Image -->
        <div class="row mb-4">
            <div class="col-md-8">
                <p><strong>ক্রেতার নাম:</strong> {{ $customer->customer_name }}</p>
                <p><strong>মোবাইল:</strong> {{ $customer->customer_phone }}</p>
                <p><strong>ঠিকানা:</strong> {{ $customer->location->name ?? 'N/A' }}</p>
                <p><strong>পণ্যের নাম:</strong> {{ $product->product_name }}</p>
            </div>
            <div class="col-md-4 customer-image-wrapper">
                <img src="{{ asset($customer->customer_image) }}" alt="Customer Image" class="rounded-img">
            </div>
        </div>

        <!-- Installment Table -->
        <div class="table-responsive">
            <table class="table">
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

        <!-- Signature Section -->
        <div class="row mt-5">
            <div class="col-md-6 signature">
                ___________________________<br>
                ক্রেতার স্বাক্ষর
            </div>
            <div class="col-md-6 signature">
                ___________________________<br>
                বিক্রেতার স্বাক্ষর
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
