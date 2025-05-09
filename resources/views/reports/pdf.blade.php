<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">

    <style>
        * {
            font-family: 'solaimanlipi', sans-serif;

        }
    </style>
</head>

<body>

    <div class="container">
        <div class="text-center">
            <h1 class="">
                রোমান ইলেকট্রিক এন্ড ফার্নিচার
            </h1>
            <b>লক্ষীপুরা রোড , বায়তুল ওমর জামে মসজিদ, (তিন রাস্তার মোড়), জয়দেবপুর, গাজীপুর। </b>
            <p>মোবাইল- ০১৮৭৫-৯৫৯২১৮</p>
        </div>


        <div class="row">
            <div class="col-md-8">
                <h2 class="customer-info">
                    <p><strong>ক্রেতার নাম:</strong> {{ $customer->customer_name }}</p>
                    <p><strong>মোবাইল:</strong> {{ $customer->customer_phone }}</p>
                    <p><strong>ঠিকানা:</strong> {{ $customer->location->name ?? 'N/A' }}</p>
                    <p><strong>পণ্যের নাম:</strong> {{ $product->product_name }}</p>
                </h2>
            </div>
            <div class="col-md-4">
                <img src="{{ asset($customer->customer_image) }}" alt="customer logo" style="border-radius: 50%; width:100px">
            </div>

            <div class="col-md-12">
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
            </div>

            <div class="col-md-6">
                <div class="signature">
                    ___________________________<br>
                    ক্রেতার স্বাক্ষর
                </div>
            </div>

            <div class="col-md-6">
                <div class="signature">
                    ___________________________
                    বিক্রেতার স্বাক্ষর
                </div>
            </div>


        </div>

    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous">
    </script>
</body>

</html>
