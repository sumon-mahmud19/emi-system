<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Installment Details</title>
</head>
<body>
    <h1>EMI Details for Purchase ID: {{ $purchase->id }}</h1>
    <p>Customer: {{ $purchase->customer->customer_name }}</p>
    <p>Product: {{ $purchase->product->name }}</p>
    <p>Total Price: {{ $purchase->total_price }} Taka</p>
    <p>Down Payment: {{ $purchase->down_payment }} Taka</p>
    <p>EMI Amount per Month: {{ $emiAmount }} Taka</p>
    <p>Number of Installments: {{ $purchase->emi_plan }}</p>

    <h3>Installments:</h3>
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>Installment #</th>
                <th>Amount</th>
                <th>Due Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($installments as $index => $installment)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $installment->amount }} Taka</td>
                    <td>{{ $installment->due_date->format('d-m-Y') }}</td>
                    <td>{{ ucfirst($installment->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
