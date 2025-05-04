<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>&#x987;&#x9A8;&#x9AD;&#x9DF;&#x9C7;&#x9B8;</title>

    <style>
        @font-face {
            font-family: 'notosansbengali';
            font-style: normal;
            font-weight: normal;
            src: url('{{ public_path('fonts/NotoSansBengali-Regular.ttf') }}') format('truetype');
        }

        body {
            font-family: 'notosansbengali', sans-serif;
            font-size: 16px;
            line-height: 26px;
            direction: ltr;
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

        .footer-signature {
            display: flex;
            justify-content: space-between;
            margin-top: 50px;
        }

        .signature-box {
            width: 45%;
            text-align: center;
        }

        .signature-line {
            border-top: 1px solid #000;
            margin-top: 60px;
            padding-top: 5px;
        }
    </style>
</head>
<body>

    <div class="invoice-header">
        &#x987;&#x9A8;&#x9AD;&#x9DF;&#x9C7;&#x9B8; - Roman Electronics & Furnitures
    </div>

    <div class="section-title">&#x995;&#x9BE;&#x9B8;&#x9CD;&#x99F;&#x9AE;&#x9BE;&#x9B0; &#x9A4;&#x9A5;&#x9CD;&#x9AF;</div>
    <table>
        <tr>
            <th>&#x9A8;&#x9BE;&#x9AE;</th>
            <td>{{ $customer->customer_name }}</td>
        </tr>
        <tr>
            <th>&#x9AB;&#x9CB;&#x9A8;</th>
            <td>{{ $customer->customer_phone }}</td>
        </tr>
        <tr>
            <th>&#x9B2;&#x9CB;&#x995;&#x9C7;&#x9B6;&#x9A8;</th>
            <td>{{ $customer->location->name ?? 'N/A' }}</td>
        </tr>
    </table>

    <div class="section-title">&#x9AA;&#x9A3;&#x9CD;&#x9AF;&#x9C7;&#x9B0; &#x9A4;&#x9A5;&#x9CD;&#x9AF;</div>
    <table>
        <tr>
            <th>&#x9AA;&#x9A3;&#x9CD;&#x9AF;&#x9C7;&#x9B0; &#x9A8;&#x9BE;&#x9AE;</th>
            <td>{{ $product->name }}</td>
        </tr>
        <tr>
            <th>&#x9AE;&#x9A1;&#x9C7;&#x9B2;</th>
            <td>{{ $purchase->model->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>&#x9AE;&#x9C2;&#x9B2;&#x9CD;&#x9AF;</th>
            <td>{{ number_format($purchase->total_price) }} &#x9A4;&#x9BE;&#x998;&#xBE;</td>
        </tr>
        <tr>
            <th>&#x9AC;&#x9BF;&#x995;&#x9CD;&#x9B0;&#x9DF; &#x9AE;&#x9C2;&#x9B2;&#x9CD;&#x9AF;</th>
            <td>{{ number_format($purchase->sales_price) }} &#x9A4;&#x9BE;&#x998;&#xBE;</td>
        </tr>
        <tr>
            <th>&#x9A1;&#x9BE;&#x0989;&#x9A8; &#x9AA;&#x9C7;&#x9AE;&#x9C7;&#x9A8;&#x9CD;&#x99F;</th>
            <td>{{ number_format($purchase->down_payment) }} &#x9A4;&#x9BE;&#x998;&#xBE;</td>
        </tr>
        <tr>
            <th>EMI &#x9B8;&#x9B9;&#x9CD;&#x9AF;&#x9BE;</th>
            <td>{{ $purchase->emi_plan }} &#x9AE;&#x9BE;&#x9B8;</td>
        </tr>
        <tr>
            <th>&#x9AA;&#x9CD;&#x9B0;&#x9A4;&#x9BF; &#x9AE;&#x9BE;&#x9B8;&#x947; &#x995;&#x9BF;&#x9B8;&#x9CD;&#x9A4;&#x9BF;</th>
            <td>{{ number_format($emiAmount) }} &#x9A4;&#x9BE;&#x998;&#xBE;</td>
        </tr>
    </table>

    <div class="section-title">EMI &#x995;&#x9BF;&#x9B8;&#x9CD;&#x9A4;&#x9BF;&#x9B0; &#x9A4;&#x9BE;&#x9B2;&#x9BF;&#x995;&#x9BE;</div>
    <table>
        <thead>
            <tr>
                <th>&#x995;&#x9CD;&#x9B0;&#x9AE;&#x9BF;&#x0995;</th>
                <th>&#x9A4;&#x9BE;&#x9B0;&#x9BF;&#x996;</th>
                <th>&#x9AA;&#x9B0;&#x9BF;&#x9AE;&#x9BE;&#x9A3;</th>
                <th>&#x985;&#x9AC;&#x9B8;&#x9CD;&#x9A5;&#x9BE;</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($installments as $index => $inst)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($inst->due_date)->format('F Y') }}</td>
                <td>{{ number_format($inst->amount) }} &#x9A4;&#x9BE;&#x998;&#xBE;</td>
                <td>
                    @if ($inst->status == 'paid')
                        &#x9AA;&#x9B0;&#x9BF;&#x9B6;&#x9CB;&#x9A7;&#x9BF;&#x9A4;
                    @else
                        &#x9AC;&#x0995;&#x9C7;&#x9DF;&#x9BE;
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer-signature">
        <div class="signature-box">
            <div class="signature-line">&#x99F;&#x9CD;&#x9B0;&#x9C7;&#x9A4;&#x9BE; &#x9B8;&#x9CD;&#x9AC;&#x9BE;&#x995;&#x9CD;&#x9B7;&#x9B0;</div>
        </div>
        <div class="signature-box">
            <div class="signature-line">&#x9AC;&#x9BF;&#x9A4;&#x9CD;&#x9B0;&#x9C7;&#x9A4;&#x9BE;&#x9B0; &#x9B8;&#x9CD;&#x9AC;&#x9BE;&#x995;&#x9CD;&#x9B7;&#x9B0;</div>
        </div>
    </div>

    <div style="text-align:center; margin-top: 30px;">
        <strong>&#x9B8;&#x9B0;&#x9CD;&#x9AC;&#x9B8;&#x7D71;&#x9CD;&#x9A4;&#x9CD;&#x9AC; &#x9B8;&#x902;&#x9B0;&#x996;&#x9BF;&#x9A4; © {{ date('Y') }} - Roman Electronics & Furnitures</strong>
    </div>

</body>
</html>
