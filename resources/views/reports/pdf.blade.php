<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>

    <h1>রোমান ইলেকট্রনিক্স এবং আসবাবপত্র</h1>

    <table>
        <tr>
            <th>পণ্যের নাম</th>
            <th>পণ্যের দাম</th>
            <th>মাস</th>
        </tr>

        <tr>
            <td>
                মাস hello
            </td>
        </tr>

        <h1>hello world</h1>

        <p><strong>Customer Name:</strong> {{ $customer->customer_name }}</p>
        <p><strong>Customer Phone:</strong> {{ $customer->customer_phone }}</p>
        <p><strong>Product:</strong> {{ $product->product_name }}</p>
        <p><strong>Total EMI Amount:</strong> {{ number_format($emiAmount, 2) }}</p>


        @foreach ($installments as $index => $installment)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($installment->installment_date)->format('d-m-Y') }}</td>
                <td>{{ number_format($installment->installment_amount, 2) }}</td>
                <td>{{ $installment->is_paid ? 'Paid' : 'Due' }}</td>
            </tr>
        @endforeach

        <tfoot>
            <tr>
                <td>
                    ক্রেতার স্বাক্ষর
                </td>
                <td>

                </td>
                <td>
                    বিক্রেতার স্বাক্ষরhhhh
                </td>
            </tr>
        </tfoot>
    </table>
</body>

</html>
