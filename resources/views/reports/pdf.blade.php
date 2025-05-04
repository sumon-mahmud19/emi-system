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

        * {
            font-family: 'Roboto', sans-serif;
            line-height: 26px;
            font-size: 15px
        }

        .custom--table {
            width: 100%;
            color: inherit;
            vertical-align: top;
            font-weight: 400;
            border-collapse: collapse;
            border-bottom: 2px solid #ddd;
            margin-top: 0;
        }

        .table-title {
            font-size: 18px;
            font-weight: 600;
            line-height: 32px;
            margin-bottom: 10px
        }

        .custom--table thead {
            font-weight: 700;
            background: inherit;
            color: inherit;
            font-size: 16px;
            font-weight: 500
        }

        .custom--table tbody {
            border-top: 0;
            overflow: hidden;
            border-radius: 10px;
        }

        .custom--table thead tr {
            border-top: 2px solid #ddd;
            border-bottom: 2px solid #ddd;
            text-align: left
        }

        .custom--table thead tr th {
            border-top: 2px solid #ddd;
            border-bottom: 2px solid #ddd;
            text-align: left;
            font-size: 16px;
            padding: 10px 0
        }

        .custom--table tbody tr {
            vertical-align: top;
        }

        .custom--table tbody tr td {
            font-size: 14px;
            line-height: 18px vertical-align:top 10
        }

        .custom--table tbody tr td:last-child {
            padding-bottom: 10px;
        }

        .custom--table tbody tr td .data-span {
            font-size: 14px;
            font-weight: 500;
            line-height: 18px;
        }

        .custom--table tbody .table_footer_row {
            border-top: 2px solid #ddd;
            margin-bottom: 10px !important;
            padding-bottom: 10px !important
        }

        /* invoice area */
        .invoice-area {
            padding: 10px 0
        }

        .invoice-wrapper {
            max-width: 650px;
            margin: 0 auto;
            box-shadow: 0 0 10px #f3f3f3;
            padding: 0px;
        }

        .invoice-header {
            margin-bottom: 40px;
        }

        .invoice-flex-contents {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
            flex-wrap: wrap;
        }

        .invoice-title {
            font-size: 25px;
            font-weight: 700
        }

        .invoice-details {
            margin-top: 20px
        }

        .invoice-details-flex {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 24px;
            flex-wrap: wrap;
        }

        .invoice-details-title {
            font-size: 18px;
            font-weight: 700;
            line-height: 32px;
            color: #333;
            margin: 0;
            padding: 0
        }

        .invoice-single-details {
            padding: 10px
        }

        .details-list {
            margin: 0;
            padding: 0;
            list-style: none;
            margin-top: 10px;
        }

        .details-list .list {
            font-size: 14px;
            font-weight: 400;
            line-height: 18px;
            color: #666;
            margin: 0;
            padding: 0;
            transition: all .3s;
        }

        .details-list .list strong {
            font-size: 14px;
            font-weight: 500;
            line-height: 18px;
            color: #666;
            margin: 0;
            padding: 0;
            transition: all .3s
        }

        .details-list .list a {
            display: inline-block;
            color: #666;
            transition: all .3s;
            text-decoration: none;
            margin: 0;
            line-height: 18px
        }

        .item-description {
            margin-top: 10px;
            padding: 10px;
        }

        .products-item {
            text-align: left
        }

        .invoice-total-count .list-single {
            display: flex;
            align-items: center;
            gap: 30px;
            font-size: 16px;
            line-height: 28px
        }

        .invoice-subtotal {
            border-bottom: 2px solid #ddd;
            padding-bottom: 15px
        }

        .invoice-total {
            padding-top: 10px
        }

        .invoice-flex-footer {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 30px;
        }

        .single-footer-item {
            flex: 1
        }

        .single-footer {
            display: flex;
            align-items: center;
            gap: 10px
        }

        .single-footer .icon {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 30px;
            width: 30px;
            font-size: 16px;
            background-color: #000e8f;
            color: #fff
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
