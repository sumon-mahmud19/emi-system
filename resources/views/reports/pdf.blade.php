<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EMI Invoice</title>
    <link rel="stylesheet" href="{{ public_path('style/invoice.css') }}" type="text/css"> 
</head>
<body>

    <table class="table-no-border">
        <tr>
            <td class="width-70">
                @if ($customer && $customer->customer_image)
                    <img src="{{ public_path($customer->customer_image) }}" alt="Customer Image" width="100">
                @else
                    <img src="{{ public_path('image/default.png') }}" alt="Default Image" width="100">
                @endif
            </td>
            <td class="width-30">
                <h2>Invoice ID: {{ $purchase->id }}</h2>
                <div><strong>Date:</strong> {{ \Carbon\Carbon::parse($purchase->created_at)->format('d M Y') }}</div>
            </td>
        </tr>
    </table>

    <div class="margin-top">
        <table class="table-no-border">
            <tr>
                <td class="width-50">
                    <div><strong>Customer Info:</strong></div>
                    <div>{{ $customer->customer_name }}</div>
                    <div>{{ $customer->location->name ?? 'N/A' }}</div>
                    <div><strong>Phone:</strong> {{ $customer->customer_phone }}</div>
                </td>
                <td class="width-50">
                    <div><strong>Product Info:</strong></div>
                    <div>{{ $product->product_name }}</div>
                    <div><strong>Details:</strong> {{ $product->product_details ?? 'N/A' }}</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="margin-top">
        <table class="product-table">
            <thead>
                <tr>
                    <th>Total Price</th>
                    <th>Down Payment</th>
                    <th>EMI Plan</th>
                    <th>Monthly EMI</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ number_format($purchase->total_price, 2) }}</td>
                    <td>{{ number_format($purchase->down_payment, 2) }}</td>
                    <td>{{ $purchase->emi_plan }} months</td>
                    <td>{{ number_format($emiAmount, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="margin-top">
        <table class="product-table">
            <thead>
                <tr>
                    <th>Installment No.</th>
                    <th>Amount</th>
                    <th>Due Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($installments as $installment)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ number_format($installment->amount, 2) }}</td>
                        <td>{{ \Carbon\Carbon::parse($installment->due_date)->format('d M Y') }}</td>
                        <td>{{ ucfirst($installment->status) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer-div">
        <p>Thank you for your purchase!<br>Roman Electronics & Furniture</p>
    </div>

</body>
</html>
