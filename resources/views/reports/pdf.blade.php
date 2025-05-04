<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice</title>

    <style>
        @font-face {
            font-family: 'NotoSans';
            src: url('{{ public_path('fonts/NotoSans-Regular.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        body {
            font-family: 'NotoSans', sans-serif;
            font-size: 14px;
            color: #333;
            margin: 20px;
        }

        .header {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 30px;
            color: #2c3e50;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
            margin-top: 20px;
            margin-bottom: 10px;
            border-bottom: 2px solid #3498db;
            padding-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th {
            background-color: #3498db;
            color: white;
            text-align: left;
            padding: 8px;
        }

        td {
            padding: 8px;
            border: 1px solid #ccc;
        }

        .footer {
            text-align: center;
            font-size: 13px;
            color: #888;
            margin-top: 40px;
        }

        .signatures {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }

        .signature-box {
            text-align: center;
            width: 45%;
        }

        .signature-line {
            margin-top: 40px;
            border-top: 1px solid #000;
            padding-top: 5px;
        }
    </style>
</head>
<body>

    <div class="header">
        Invoice - Roman Electronics & Furnitures
    </div>

    <div class="section-title">Customer Information</div>
    <table>
        <tr>
            <th>Name</th>
            <td>{{ $customer->customer_name }}</td>
        </tr>
        <tr>
            <th>Phone</th>
            <td>{{ $customer->customer_phone }}</td>
        </tr>
        <tr>
            <th>Location</th>
            <td>{{ $customer->location->name ?? 'N/A' }}</td>
        </tr>
    </table>

    <div class="section-title">Product Information</div>
    <table>
        <tr>
            <th>Product Name</th>
            <td>{{ $product->name }}</td>
        </tr>
        <tr>
            <th>Model</th>
            <td>{{ $purchase->model->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Total Price</th>
            <td>{{ number_format($purchase->total_price) }} Tk</td>
        </tr>
        <tr>
            <th>Sales Price</th>
            <td>{{ number_format($purchase->sales_price) }} Tk</td>
        </tr>
        <tr>
            <th>Down Payment</th>
            <td>{{ number_format($purchase->down_payment) }} Tk</td>
        </tr>
        <tr>
            <th>EMI Duration</th>
            <td>{{ $purchase->emi_plan }} months</td>
        </tr>
        <tr>
            <th>Monthly Installment</th>
            <td>{{ number_format($emiAmount) }} Tk</td>
        </tr>
    </table>

    <div class="section-title">EMI Schedule</div>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Due Date</th>
                <th>Amount</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($installments as $index => $inst)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($inst->due_date)->format('F Y') }}</td>
                <td>{{ number_format($inst->amount) }} Tk</td>
                <td>
                    @if ($inst->status == 'paid')
                        Paid
                    @else
                        Due
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="signatures">
        <div class="signature-box">
            <div class="signature-line">Customer Signature</div>
        </div>
        <div class="signature-box">
            <div class="signature-line">Seller Signature</div>
        </div>
    </div>

    <div class="footer">
        &copy; {{ date('Y') }} Roman Electronics & Furnitures. All rights reserved.
    </div>

</body>
</html>
