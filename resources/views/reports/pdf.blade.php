<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <style>
        @font-face {
            font-family: 'notosansbengali';
            src: url('{{ storage_path('fonts/NotoSansBengali-Regular.ttf') }}') format('truetype');
        }

        body {
            font-family: 'notosansbengali', sans-serif;
            font-size: 16px;
            line-height: 26px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 8px;
            border: 1px solid #ccc;
            text-align: left;
        }

        .section-title {
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
        }

        .invoice-header {
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <div class="invoice-header">
    {{ $invoice->header_name }}
    </div>

    <div class="section-title">{{ $invoice->bill_to }}</div>
    <table>
        <tr>
            <th>{{ $invoice->name }}</th>
            <td>{{ $customer->customer_name }}</td>
        </tr>
        <tr>
            <th>{{ $invoice->phone }}</th>
            <td>{{ $customer->customer_phone }}</td>
        </tr>
        <tr>
            <th>{{ $invoice->location }}</th>
            <td>{{ $customer->location->name ?? 'N/A' }}</td>
        </tr>
    </table>

    <div class="section-title"></div>
    <table>
        <tr>
            <th> {{ $invoice->product_name }}</th>
            <td>{{ $product->name }}</td>
        </tr>
        <tr>
            <th>{{ $invoice->product_model }}</th>
            <td>{{ $purchase->model->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>{{ $invoice->total_price }}</th>
            <td>{{ number_format($purchase->total_price) }} টাকা</td>
        </tr>

        <tr>
            <th> {{ $invoice->down_payment }}</th>
            <td>{{ number_format($purchase->down_payment) }} টাকা</td>
        </tr>
        <tr>
            <th>{{ $invoice->emi_month }}</th>
            <td>{{ $purchase->emi_plan }} মাস</td>
        </tr>
        <tr>
            <th>{{ $invoice->next_emi_amount }}</th>
            <td>{{ number_format($emiAmount) }} টাকা</td>
        </tr>
    </table>

    <div class="section-title">EMI কিস্তির তালিকা</div>
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
            @foreach ($installments as $index => $inst)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($inst->due_date)->format('F Y') }}</td>
                <td>{{ number_format($inst->amount) }} টাকা</td>
                <td>{{ ucfirst($inst->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="text-align:center; margin-top: 30px;">
        <strong>সর্বস্বত্ব সংরক্ষিত © {{ date('Y') }} - Roman Electronics & Furnitures</strong>
    </div>

</body>
</html>
