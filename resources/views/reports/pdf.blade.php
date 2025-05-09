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

        img.rounded-img {
            border-radius: 50%;
            width: 100px;
            height: 100px;
            object-fit: cover;
            border: 2px solid #ccc;
        }

        .signature {
            margin-top: 50px;
            text-align: center;
        }

        table {
            margin-top: 20px;
        }

        th, td {
            text-align: center;
        }
    </style>
</head>

<body>

    <div class="container py-4">
        <div class="text-center mb-4">
            <h2>রোমান ইলেকট্রিক এন্ড ফার্নিচার</h2>
            <strong>লক্ষীপুরা রোড , বায়তুল ওমর জামে মসজিদ, (তিন রাস্তার মোড়), জয়দেবপুর, গাজীপুর।</strong><br>
            <p>মোবাইল- ০১৮৭৫-৯৫৯২১৮</p>
        </div>

        <div class="row mb-3">
            <div class="col-md-8">
                <div class="customer-info">
                    <p><strong>ক্রেতার নাম:</strong> {{ $customer->customer_name }}</p>
                    <p><strong>মোবাইল:</strong> {{ $customer->customer_phone }}</p>
                    <p><strong>ঠিকানা:</strong> {{ $customer->location->name ?? 'N/A' }}</p>
                    <p><strong>পণ্যের নাম:</strong> {{ $product->product_name }}</p>
                </div>
            </div>
            <div class="col-md-4 text-end">
                <img src="{{ asset($customer->customer_image) }}" class="rounded-img" alt="Customer Image">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered">
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
