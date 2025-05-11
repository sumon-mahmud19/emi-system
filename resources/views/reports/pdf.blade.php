
        * {
            font-family: 'solaimanlipi', sans-serif;
        }

       <!DOCTYPE html>
       <html lang="en">
       <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Roman Emi Invoice</title>
        <style>
            *{
                font-family: 'solaimanlipi', sans-serif !important;
                background-color: red;
            }
        </style>
       </head>
       <body>
        
        <header>
            <center>
                <h3>রোমান ইলেকট্রিক এন্ড ফার্নিচার</h3>
                <div>লক্ষীপুরা রোড, বায়তুল ওমর জামে মসজিদ, (তিন রাস্তার মোড়), জয়দেবপুর, গাজীপুর।</div>
                <div>মোবাইল: ০১৮৭৫-৯৫৯২১৮</div>
            </center>
        </header>
        <table>
            <tr>
                <th>Customer Info:</th>
                <th>
                    Customer Image
                </th>
            </tr>

            <tr>
                <td>
                    <p><strong>ক্রেতার নাম:</strong> {{ $customer->customer_name }}</p>
                    <p><strong>মোবাইল:</strong> {{ $customer->customer_phone }}</p>
                    <p><strong>ঠিকানা:</strong> {{ $customer->location->name ?? 'N/A' }}</p>
                    <p><strong>পণ্যের নাম:</strong> {{ $product->product_name }}</p>
                </td>
                <td>
                    <img src="{{ $customer->customer_image}}" alt="logo" width="100px" style="border-radius: 50%">
                </td>
            </tr>

            <tr>
                <th>ক্রমিক</th>
                <th>তারিখ</th>
                <th>পরিমাণ</th>
                <th>অবস্থা</th>
            </tr>

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


            <tr>
                <td>
                    ___________________________<br>ক্রেতার স্বাক্ষর
                </td>
                <td>
                    ___________________________<br>বিক্রেতার স্বাক্ষর
                </td>

            </tr>
        </table>
       </body>
       </html>