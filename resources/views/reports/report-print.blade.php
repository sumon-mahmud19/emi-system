text/x-generic report-print.blade.php ( HTML document, UTF-8 Unicode text, with CRLF line terminators )
<!DOCTYPE html>
<html lang="bn">

<head>
    <meta charset="UTF-8">
    <title>
        {{ $customer->customer_name }}
    </title>
    <style>
        * {
            margin: 0;
            padding: 0;

        }

        table {

            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            font-size: 12.5px;
            border: 1px solid #ccc;
            padding: 2px 2px;
            /* 🔹 smaller padding */
            text-align: center;
            vertical-align: middle;
            line-height: 9px;
            /* 🔹 keeps text compact */
            height: 10px;
            /* 🔹 target height */
        }

        tr {
            height: 10px;
            /* 🔹 reduced from 30px */
        }

        thead {
            background-color: #f8f9fa;
        }

        tbody tr:nth-child(even) {
            background-color: #fff;
        }
    </style>

</head>

<body>


    @if ($customer->purchases->count() <= 0)

        <table>
            <tr>
                <td
                    style=
                "
                display:flex;
                align-items: center;
                justify-content: center;
                border: none;
                padding-top: 5rem;
                font-size: 20px;
                
                ">
                    Not Found Report!
                </td>
            </tr>
        </table>
    @else
        @php
            $sum = 0;
            $emp_months = 0;

            #   // Get EMI months (once)
            foreach ($customer->purchases as $purchase) {
                $emp_months = $purchase->emi_plan;
                break; // only need the first one
            }

            // Calculate total for all products
            foreach ($customer->purchases as $purchase) {
                $totalPrice = $purchase->net_price;
                $sum += $totalPrice;
            }

            // Show total EMI per month
            $result = round($sum / $emp_months);

        @endphp


        <div class="two-columns">
            <!-- Left Column -->
            <div class="column">
                <table>
                    <thead>
                        <tr style="height: 10px;">
                            <th colspan="2" style="height: 10px;">পৃ:নং: {{ $customer->customer_id }}</th>
                            <th colspan="4" style="height: 10px;">ফোন নং: {{ $customer->customer_phone }}</th>
                            <th colspan="4" style="height: 10px;">কিস্তির পরিমান: {{ $result . ' টাকা' }}</th>
                            <th rowspan="2">
                                <img src="{{ $customer->customer_image }}" alt="Customer Image"
                                    style="border-radius: 50%; width: 80px; height: 80px; object-fit: cover;">
                            </th>
                        </tr>

                    </thead>

                    <tbody>

                        <tr>
                            <th colspan="6">নাম: {{ $customer->customer_name }}</th>
                            @foreach ($customer->purchases as $purchase)
                                <th colspan="4">কিস্তির মেয়াদ: {{ $purchase->emi_plan . ' মাস' }}</th>
                            @break
                        @endforeach

                    </tr>

                    <tr>
                        <th colspan="5">ঠিকানা: {{ $customer->location->name ?? 'N/A' }} ({{$customer->landlord_name}})  </th>
                        <th colspan="6">
                            @foreach ($customer->purchases as $purchase)
                                <p>
                                    কিস্তির তারিখ: {{ $purchase->created_at->addMonth()->format('F Y') }}
                                </p>
                            @break
                        @endforeach

                    </th>
                </tr>

                <tr>
                    @php
                        $color = '#ddd';

                    @endphp
                    <td
                        style="padding: 2px; border:2px solid 
                    @php
echo $color; @endphp
                    ">
                        <b>ক্রমিক নং:</b>
                    </td>
                    <td style="padding: 2px; border:2px solid 
                    @php
echo $color; @endphp
                    "
                        colspan="2"><b>পণ্যের বিবরণ:</b></td>
                    <td
                        style="padding: 2px; border:2px solid 
                    @php
echo $color; @endphp
                    ">
                        <b>কিস্তি মূল্য:</b>
                    </td>
                    <td
                        style="padding: 2px; border:2px solid 
                    @php
echo $color; @endphp
                    ">
                        <b>মোট মূল্য:</b>
                    </td>
                    <td
                        style="padding: 2px; border:2px solid 
                    @php
echo $color; @endphp
                    ">
                        <b>তারিখ:</b>
                    </td>
                    <td
                        style="padding: 2px; border:2px solid 
                    @php
echo $color; @endphp
                    ">
                        <b>জমা: </b>
                    </td>
                    <td
                        style="padding: 2px; border:2px solid 
                    @php
echo $color; @endphp
                    ">
                        <b>মোট জমা:</b>
                    </td>
                    <td
                        style="padding: 2px; border:2px solid 
                    @php
echo $color; @endphp
                    ">
                        <b>বাকি:</b>
                    </td>
                    <td style="padding: 2px; border:2px solid 
                    @php
echo $color; @endphp
                    "
                        width="10%;"><b>আউটর স্বাক্ষর:</b></td>
                    <td
                        style="padding: 2px; border:2px solid 
                    @php
echo $color; @endphp
                    ">
                        <b>মন্তব্য:<b>
                    </td>
                    {{-- <td></td> --}}
                </tr>

                @php
                    $grandTotalPrice = 0;
                    $grandTotalPaid = 0;
                    $grandTotalDue = 0;
                    $grandTotalDown = 0;
                    $count = 1;
                @endphp

                @if ($customer->purchases->count() <= 1)

                    @foreach ($customer->purchases as $purchase)
                        @php
                            $product = $purchase->product;
                            $totalPrice = $purchase->net_price;
                            $down = $purchase->down_price;
                            $totalPaid = $purchase->installments->sum('paid_amount');
                            $totalDue = $purchase->installments->sum(fn($i) => $i->amount - $i->paid_amount);
                            $totalDeposit = $totalPaid + $down;
                            $grandTotalPrice += $totalPrice;
                            $grandTotalPaid += $totalPaid;
                            $grandTotalDown += $down;
                            $grandTotalDue += $totalDue;

                        @endphp
                        <tr>
                            <td>{{ $count++ }}</td>
                            <td colspan="2">{{ $product->product_name }}</td>
                            <td>{{ number_format($totalPrice, 2) }} ৳</td>
                            <td>{{ number_format($totalPrice, 2) }} ৳</td>
                            <td>{{ \Carbon\Carbon::parse($purchase->created_at)->format('d-m-Y') }}</td>
                            <td>{{ number_format($totalDeposit, 2) }} ৳</td>
                            <td>{{ number_format($totalDeposit, 2) }} ৳</td>
                            <td>{{ number_format($totalDue, 2) }} ৳</td>
                            {{-- <td>lkjljl</td> --}}
                            {{-- <td></td> --}}
                        </tr>
                    @endforeach
                    <tr>
                        <td style="height: 15px">{{ $count++ }}</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="height: 15px">{{ $count++ }}</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="height: 15px">{{ $count++ }}</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="height: 15px">{{ $count++ }}</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="height: 15px">{{ $count++ }}</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="height: 15px">{{ $count++ }}</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="height: 15px">{{ $count++ }}</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="height: 15px">{{ $count++ }}</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="height: 15px">{{ $count++ }}</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="height: 15px">{{ $count++ }}</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="height: 15px">{{ $count++ }}</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                @elseif ($customer->purchases->count() <= 2)
                    @foreach ($customer->purchases as $purchase)
                        @php
                            $product = $purchase->product;
                            $totalPrice = $purchase->net_price;
                            $down = $purchase->down_price;
                            $totalPaid = $purchase->installments->sum('paid_amount');
                            $totalDue = $purchase->installments->sum(fn($i) => $i->amount - $i->paid_amount);
                            $totalDeposit = $totalPaid + $down;
                            $grandTotalPrice += $totalPrice;
                            $grandTotalPaid += $totalPaid;
                            $grandTotalDown += $down;
                            $grandTotalDue += $totalDue;
                        @endphp
                        <tr>
                            <td>{{ $count++ }}</td>
                            <td colspan="2">{{ $product->product_name }}</td>
                            <td>{{ number_format($totalPrice, 2) }} ৳</td>
                            <td>{{ number_format($totalPrice, 2) }} ৳</td>
                            <td>{{ \Carbon\Carbon::parse($purchase->created_at)->format('d-m-Y') }}</td>
                            <td>{{ number_format($totalDeposit, 2) }} ৳</td>
                            <td>{{ number_format($totalDeposit, 2) }} ৳</td>
                            <td>{{ number_format($totalDue, 2) }} ৳</td>
                            <td></td>
                            <td></td>
                        </tr>
                    @endforeach

                    <tr>
                        <td style="height: 15px">{{ $count++ }}</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="height: 15px">{{ $count++ }}</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="height: 15px">{{ $count++ }}</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="height: 15px">{{ $count++ }}</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="height: 15px">{{ $count++ }}</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="height: 15px">{{ $count++ }}</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="height: 15px">{{ $count++ }}</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="height: 15px">{{ $count++ }}</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="height: 15px">{{ $count++ }}</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="height: 15px">{{ $count++ }}</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                @elseif ($customer->purchases->count() <= 3)
                    @foreach ($customer->purchases as $purchase)
                        @php
                            $product = $purchase->product;
                            $totalPrice = $purchase->net_price;
                            $down = $purchase->down_price;
                            $totalPaid = $purchase->installments->sum('paid_amount');
                            $totalDue = $purchase->installments->sum(fn($i) => $i->amount - $i->paid_amount);
                            $totalDeposit = $totalPaid + $down;
                            $grandTotalPrice += $totalPrice;
                            $grandTotalPaid += $totalPaid;
                            $grandTotalDown += $down;
                            $grandTotalDue += $totalDue;
                        @endphp
                        <tr>
                            <td>{{ $count++ }}</td>
                            <td colspan="2">{{ $product->product_name }}</td>
                            <td>{{ number_format($totalPrice, 2) }} ৳</td>
                            <td>{{ number_format($totalPrice, 2) }} ৳</td>
                            <td>{{ \Carbon\Carbon::parse($purchase->created_at)->format('d-m-Y') }}</td>
                            <td>{{ number_format($totalDeposit, 2) }} ৳</td>
                            <td>{{ number_format($totalDeposit, 2) }} ৳</td>
                            <td>{{ number_format($totalDue, 2) }} ৳</td>
                            <td></td>
                            <td></td>
                        </tr>
                    @endforeach

                    <tr>
                        <td style="height: 15px">{{ $count++ }}</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="height: 15px">{{ $count++ }}</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="height: 15px">{{ $count++ }}</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="height: 15px">{{ $count++ }}</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="height: 15px">{{ $count++ }}</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="height: 15px">{{ $count++ }}</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="height: 15px">{{ $count++ }}</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="height: 15px">{{ $count++ }}</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="height: 15px">{{ $count++ }}</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                @elseif ($customer->purchases->count() <= 4)
                    @foreach ($customer->purchases as $purchase)
                        @php
                            $product = $purchase->product;
                            $totalPrice = $purchase->net_price;
                            $down = $purchase->down_price;
                            $totalPaid = $purchase->installments->sum('paid_amount');
                            $totalDue = $purchase->installments->sum(fn($i) => $i->amount - $i->paid_amount);
                            $totalDeposit = $totalPaid + $down;
                            $grandTotalPrice += $totalPrice;
                            $grandTotalPaid += $totalPaid;
                            $grandTotalDown += $down;
                            $grandTotalDue += $totalDue;
                        @endphp
                        <tr>
                            <td>{{ $count++ }}</td>
                            <td colspan="2">{{ $product->product_name }}</td>
                            <td>{{ number_format($totalPrice, 2) }} ৳</td>
                            <td>{{ number_format($totalPrice, 2) }} ৳</td>
                            <td>{{ \Carbon\Carbon::parse($purchase->created_at)->format('d-m-Y') }}</td>
                            <td>{{ number_format($totalDeposit, 2) }} ৳</td>
                            <td>{{ number_format($totalDeposit, 2) }} ৳</td>
                            <td>{{ number_format($totalDue, 2) }} ৳</td>
                            <td></td>
                            <td></td>
                        </tr>
                    @endforeach

                    <tr>
                        <td style="height: 15px">{{ $count++ }}</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="height: 15px">{{ $count++ }}</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="height: 15px">{{ $count++ }}</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="height: 15px">{{ $count++ }}</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="height: 15px">{{ $count++ }}</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="height: 15px">{{ $count++ }}</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="height: 15px">{{ $count++ }}</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="height: 15px">{{ $count++ }}</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                @elseif ($customer->purchases->count() <= 5)
                    @foreach ($customer->purchases as $purchase)
                        @php
                            $product = $purchase->product;
                            $totalPrice = $purchase->net_price;
                            $down = $purchase->down_price;
                            $totalPaid = $purchase->installments->sum('paid_amount');
                            $totalDue = $purchase->installments->sum(fn($i) => $i->amount - $i->paid_amount);
                            $totalDeposit = $totalPaid + $down;
                            $grandTotalPrice += $totalPrice;
                            $grandTotalPaid += $totalPaid;
                            $grandTotalDown += $down;
                            $grandTotalDue += $totalDue;
                        @endphp
                        <tr>
                            <td>{{ $count++ }}</td>
                            <td colspan="2">{{ $product->product_name }}</td>
                            <td>{{ number_format($totalPrice, 2) }} ৳</td>
                            <td>{{ number_format($totalPrice, 2) }} ৳</td>
                            <td>{{ \Carbon\Carbon::parse($purchase->created_at)->format('d-m-Y') }}</td>
                            <td>{{ number_format($totalDeposit, 2) }} ৳</td>
                            <td>{{ number_format($totalDeposit, 2) }} ৳</td>
                            <td>{{ number_format($totalDue, 2) }} ৳</td>
                            <td></td>
                            <td></td>
                        </tr>
                    @endforeach

                    <tr>
                        <td style="height: 15px">{{ $count++ }}</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="height: 15px">{{ $count++ }}</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="height: 15px">{{ $count++ }}</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="height: 15px">{{ $count++ }}</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="height: 15px">{{ $count++ }}</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="height: 15px">{{ $count++ }}</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="height: 15px">{{ $count++ }}</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                @elseif ($customer->purchases->count() <= 6)
                    @foreach ($customer->purchases as $purchase)
                        @php
                            $product = $purchase->product;
                            $totalPrice = $purchase->net_price;
                            $down = $purchase->down_price;
                            $totalPaid = $purchase->installments->sum('paid_amount');
                            $totalDue = $purchase->installments->sum(fn($i) => $i->amount - $i->paid_amount);
                            $totalDeposit = $totalPaid + $down;
                            $grandTotalPrice += $totalPrice;
                            $grandTotalPaid += $totalPaid;
                            $grandTotalDown += $down;
                            $grandTotalDue += $totalDue;
                        @endphp
                        <tr>
                            <td>{{ $count++ }}</td>
                            <td colspan="2">{{ $product->product_name }}</td>
                            <td>{{ number_format($totalPrice, 2) }} ৳</td>
                            <td>{{ number_format($totalPrice, 2) }} ৳</td>
                            <td>{{ \Carbon\Carbon::parse($purchase->created_at)->format('d-m-Y') }}</td>
                            <td>{{ number_format($totalDeposit, 2) }} ৳</td>
                            <td>{{ number_format($totalDeposit, 2) }} ৳</td>
                            <td>{{ number_format($totalDue, 2) }} ৳</td>
                            <td></td>
                            <td></td>
                        </tr>
                    @endforeach

                    <tr>
                        <td style="height: 15px">{{ $count++ }}</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="height: 15px">{{ $count++ }}</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="height: 15px">{{ $count++ }}</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="height: 15px">{{ $count++ }}</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="height: 15px">{{ $count++ }}</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="height: 15px">{{ $count++ }}</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                @elseif ($customer->purchases->count() <= 7)
                    @foreach ($customer->purchases as $purchase)
                        @php
                            $product = $purchase->product;
                            $totalPrice = $purchase->net_price;
                            $down = $purchase->down_price;
                            $totalPaid = $purchase->installments->sum('paid_amount');
                            $totalDue = $purchase->installments->sum(fn($i) => $i->amount - $i->paid_amount);
                            $totalDeposit = $totalPaid + $down;
                            $grandTotalPrice += $totalPrice;
                            $grandTotalPaid += $totalPaid;
                            $grandTotalDown += $down;
                            $grandTotalDue += $totalDue;
                        @endphp
                        <tr>
                            <td>{{ $count++ }}</td>
                            <td colspan="2">{{ $product->product_name }}</td>
                            <td>{{ number_format($totalPrice, 2) }} ৳</td>
                            <td>{{ number_format($totalPrice, 2) }} ৳</td>
                            <td>{{ \Carbon\Carbon::parse($purchase->created_at)->format('d-m-Y') }}</td>
                            <td>{{ number_format($totalDeposit, 2) }} ৳</td>
                            <td>{{ number_format($totalDeposit, 2) }} ৳</td>
                            <td>{{ number_format($totalDue, 2) }} ৳</td>
                            <td></td>
                            <td></td>
                        </tr>
                    @endforeach

                    <tr>
                        <td style="height: 15px">{{ $count++ }}</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="height: 15px">{{ $count++ }}</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="height: 15px">{{ $count++ }}</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="height: 15px">{{ $count++ }}</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="height: 15px">{{ $count++ }}</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                @elseif ($customer->purchases->count() <= 8)
                    @foreach ($customer->purchases as $purchase)
                        @php
                            $product = $purchase->product;
                            $totalPrice = $purchase->net_price;
                            $down = $purchase->down_price;
                            $totalPaid = $purchase->installments->sum('paid_amount');
                            $totalDue = $purchase->installments->sum(fn($i) => $i->amount - $i->paid_amount);
                            $totalDeposit = $totalPaid + $down;
                            $grandTotalPrice += $totalPrice;
                            $grandTotalPaid += $totalPaid;
                            $grandTotalDown += $down;
                            $grandTotalDue += $totalDue;
                        @endphp
                        <tr>
                            <td>{{ $count++ }}</td>
                            <td colspan="2">{{ $product->product_name }}</td>
                            <td>{{ number_format($totalPrice, 2) }} ৳</td>
                            <td>{{ number_format($totalPrice, 2) }} ৳</td>
                            <td>{{ \Carbon\Carbon::parse($purchase->created_at)->format('d-m-Y') }}</td>
                            <td>{{ number_format($totalDeposit, 2) }} ৳</td>
                            <td>{{ number_format($totalDeposit, 2) }} ৳</td>
                            <td>{{ number_format($totalDue, 2) }} ৳</td>
                            <td></td>
                            <td></td>
                        </tr>
                    @endforeach

                    <tr>
                        <td style="height: 15px">{{ $count++ }}</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="height: 15px">{{ $count++ }}</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="height: 15px">{{ $count++ }}</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="height: 15px">{{ $count++ }}</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                @elseif ($customer->purchases->count() <= 9)
                    @foreach ($customer->purchases as $purchase)
                        @php
                            $product = $purchase->product;
                            $totalPrice = $purchase->net_price;
                            $down = $purchase->down_price;
                            $totalPaid = $purchase->installments->sum('paid_amount');
                            $totalDue = $purchase->installments->sum(fn($i) => $i->amount - $i->paid_amount);
                            $totalDeposit = $totalPaid + $down;
                            $grandTotalPrice += $totalPrice;
                            $grandTotalPaid += $totalPaid;
                            $grandTotalDown += $down;
                            $grandTotalDue += $totalDue;
                        @endphp
                        <tr>
                            <td>{{ $count++ }}</td>
                            <td colspan="2">{{ $product->product_name }}</td>
                            <td>{{ number_format($totalPrice, 2) }} ৳</td>
                            <td>{{ number_format($totalPrice, 2) }} ৳</td>
                            <td>{{ \Carbon\Carbon::parse($purchase->created_at)->format('d-m-Y') }}</td>
                            <td>{{ number_format($totalDeposit, 2) }} ৳</td>
                            <td>{{ number_format($totalDeposit, 2) }} ৳</td>
                            <td>{{ number_format($totalDue, 2) }} ৳</td>
                            <td></td>
                            <td></td>
                        </tr>
                    @endforeach

                    <tr>
                        <td style="height: 15px">{{ $count++ }}</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="height: 15px">{{ $count++ }}</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="height: 15px">{{ $count++ }}</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                @elseif ($customer->purchases->count() <= 10)
                    @foreach ($customer->purchases as $purchase)
                        @php
                            $product = $purchase->product;
                            $totalPrice = $purchase->net_price;
                            $down = $purchase->down_price;
                            $totalPaid = $purchase->installments->sum('paid_amount');
                            $totalDue = $purchase->installments->sum(fn($i) => $i->amount - $i->paid_amount);
                            $totalDeposit = $totalPaid + $down;
                            $grandTotalPrice += $totalPrice;
                            $grandTotalPaid += $totalPaid;
                            $grandTotalDown += $down;
                            $grandTotalDue += $totalDue;
                        @endphp
                        <tr>
                            <td>{{ $count++ }}</td>
                            <td colspan="2">{{ $product->product_name }}</td>
                            <td>{{ number_format($totalPrice, 2) }} ৳</td>
                            <td>{{ number_format($totalPrice, 2) }} ৳</td>
                            <td>{{ \Carbon\Carbon::parse($purchase->created_at)->format('d-m-Y') }}</td>
                            <td>{{ number_format($totalDeposit, 2) }} ৳</td>
                            <td>{{ number_format($totalDeposit, 2) }} ৳</td>
                            <td>{{ number_format($totalDue, 2) }} ৳</td>
                            <td></td>
                            <td></td>
                        </tr>
                    @endforeach

                    <tr>
                        <td style="height: 15px">{{ $count++ }}</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="height: 15px">{{ $count++ }}</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                @elseif ($customer->purchases->count() <= 11)
                    @foreach ($customer->purchases as $purchase)
                        @php
                            $product = $purchase->product;
                            $totalPrice = $purchase->net_price;
                            $down = $purchase->down_price;
                            $totalPaid = $purchase->installments->sum('paid_amount');
                            $totalDue = $purchase->installments->sum(fn($i) => $i->amount - $i->paid_amount);
                            $totalDeposit = $totalPaid + $down;
                            $grandTotalPrice += $totalPrice;
                            $grandTotalPaid += $totalPaid;
                            $grandTotalDown += $down;
                            $grandTotalDue += $totalDue;
                        @endphp
                        <tr>
                            <td>{{ $count++ }}</td>
                            <td colspan="2">{{ $product->product_name }}</td>
                            <td>{{ number_format($totalPrice, 2) }} ৳</td>
                            <td>{{ number_format($totalPrice, 2) }} ৳</td>
                            <td>{{ \Carbon\Carbon::parse($purchase->created_at)->format('d-m-Y') }}</td>
                            <td>{{ number_format($totalDeposit, 2) }} ৳</td>
                            <td>{{ number_format($totalDeposit, 2) }} ৳</td>
                            <td>{{ number_format($totalDue, 2) }} ৳</td>
                            <td></td>
                            <td></td>
                        </tr>
                    @endforeach

                    <tr>
                        <td style="height: 15px">{{ $count++ }}</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                @else
                    <h1>data not found!</h1>

                @endif

                <tr>
                    <td colspan="4" style="border: none; padding-top: 1rem;">ক্রেতার স্বাক্ষর..........
                    </td>
                    <td colspan="6" style="text-align: right; padding-top: 1rem; border: none;">
                        বিক্রেতার স্বাক্ষর...........
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <hr>



    <div class="two-columns">
        <!-- Left Column -->
        <div class="column">
            <table>
                <thead>
                    <tr style="height: 10px;">
                        <th colspan="2" style="height: 10px;">পৃ:নং: {{ $customer->customer_id }}</th>
                        <th colspan="4" style="height: 10px;">ফোন নং: {{ $customer->customer_phone }}
                        </th>
                        <th colspan="4" style="height: 10px;">কিস্তির পরিমান: {{ $result . ' টাকা' }}
                        </th>
                        <th rowspan="2">
                            <img src="{{ $customer->customer_image }}" alt="Customer Image"
                                style="border-radius: 50%; width: 80px; height: 80px; object-fit: cover;">
                        </th>
                    </tr>

                </thead>

                <tbody>

                    <tr>
                        <th colspan="6">নাম: {{ $customer->customer_name }}</th>
                        @foreach ($customer->purchases as $purchase)
                            <th colspan="4">কিস্তির মেয়াদ: {{ $purchase->emi_plan . ' মাস' }}</th>
                        @break
                    @endforeach

                </tr>

                <tr>
                    <th colspan="5">ঠিকানা: {{ $customer->location->name ?? 'N/A' }} ({{$customer->landlord_name}})  </th>
                    <th colspan="6">
                        @foreach ($customer->purchases as $purchase)
                            <p>
                                কিস্তির তারিখ: {{ $purchase->created_at->addMonth()->format('F Y') }}
                            </p>
                        @break
                    @endforeach

                </th>
            </tr>

            <tr>
                @php
                    $color = '#ddd';

                @endphp
                <td
                    style="padding: 2px; border:2px solid 
                    @php
echo $color; @endphp
                    ">
                    <b>ক্রমিক নং:</b>
                </td>
                <td style="padding: 2px; border:2px solid 
                    @php
echo $color; @endphp
                    "
                    colspan="2"><b>পণ্যের বিবরণ:</b></td>
                <td
                    style="padding: 2px; border:2px solid 
                    @php
echo $color; @endphp
                    ">
                    <b>কিস্তি মূল্য:</b>
                </td>
                <td
                    style="padding: 2px; border:2px solid 
                    @php
echo $color; @endphp
                    ">
                    <b>মোট মূল্য:</b>
                </td>
                <td
                    style="padding: 2px; border:2px solid 
                    @php
echo $color; @endphp
                    ">
                    <b>তারিখ:</b>
                </td>
                <td
                    style="padding: 2px; border:2px solid 
                    @php
echo $color; @endphp
                    ">
                    <b>জমা: </b>
                </td>
                <td
                    style="padding: 2px; border:2px solid 
                    @php
echo $color; @endphp
                    ">
                    <b>মোট জমা:</b>
                </td>
                <td
                    style="padding: 2px; border:2px solid 
                    @php
echo $color; @endphp
                    ">
                    <b>বাকি:</b>
                </td>
                <td style="padding: 2px; border:2px solid 
                    @php
echo $color; @endphp
                    "
                    width="10%;"><b>আউটর স্বাক্ষর:</b></td>
                <td
                    style="padding: 2px; border:2px solid 
                    @php
echo $color; @endphp
                    ">
                    <b>মন্তব্য:<b>
                </td>
                {{-- <td></td> --}}
            </tr>

            @php
                $grandTotalPrice = 0;
                $grandTotalPaid = 0;
                $grandTotalDue = 0;
                $grandTotalDown = 0;
                $count = 1;
            @endphp

            @if ($customer->purchases->count() <= 1)

                @foreach ($customer->purchases as $purchase)
                    @php
                        $product = $purchase->product;
                        $totalPrice = $purchase->net_price;
                        $down = $purchase->down_price;
                        $totalPaid = $purchase->installments->sum('paid_amount');
                        $totalDue = $purchase->installments->sum(fn($i) => $i->amount - $i->paid_amount);
                        $totalDeposit = $totalPaid + $down;
                        $grandTotalPrice += $totalPrice;
                        $grandTotalPaid += $totalPaid;
                        $grandTotalDown += $down;
                        $grandTotalDue += $totalDue;

                    @endphp
                    <tr>
                        <td>{{ $count++ }}</td>
                        <td colspan="2">{{ $product->product_name }}</td>
                        <td>{{ number_format($totalPrice, 2) }} ৳</td>
                        <td>{{ number_format($totalPrice, 2) }} ৳</td>
                        <td>{{ \Carbon\Carbon::parse($purchase->created_at)->format('d-m-Y') }}</td>
                        <td>{{ number_format($totalDeposit, 2) }} ৳</td>
                        <td>{{ number_format($totalDeposit, 2) }} ৳</td>
                        <td>{{ number_format($totalDue, 2) }} ৳</td>
                        {{-- <td>lkjljl</td> --}}
                        {{-- <td></td> --}}
                    </tr>
                @endforeach
                <tr>
                    <td style="height: 15px">{{ $count++ }}</td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height: 15px">{{ $count++ }}</td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height: 15px">{{ $count++ }}</td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height: 15px">{{ $count++ }}</td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height: 15px">{{ $count++ }}</td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height: 15px">{{ $count++ }}</td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height: 15px">{{ $count++ }}</td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height: 15px">{{ $count++ }}</td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height: 15px">{{ $count++ }}</td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height: 15px">{{ $count++ }}</td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height: 15px">{{ $count++ }}</td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @elseif ($customer->purchases->count() <= 2)
                @foreach ($customer->purchases as $purchase)
                    @php
                        $product = $purchase->product;
                        $totalPrice = $purchase->net_price;
                        $down = $purchase->down_price;
                        $totalPaid = $purchase->installments->sum('paid_amount');
                        $totalDue = $purchase->installments->sum(fn($i) => $i->amount - $i->paid_amount);
                        $totalDeposit = $totalPaid + $down;
                        $grandTotalPrice += $totalPrice;
                        $grandTotalPaid += $totalPaid;
                        $grandTotalDown += $down;
                        $grandTotalDue += $totalDue;
                    @endphp
                    <tr>
                        <td>{{ $count++ }}</td>
                        <td colspan="2">{{ $product->product_name }}</td>
                        <td>{{ number_format($totalPrice, 2) }} ৳</td>
                        <td>{{ number_format($totalPrice, 2) }} ৳</td>
                        <td>{{ \Carbon\Carbon::parse($purchase->created_at)->format('d-m-Y') }}</td>
                        <td>{{ number_format($totalDeposit, 2) }} ৳</td>
                        <td>{{ number_format($totalDeposit, 2) }} ৳</td>
                        <td>{{ number_format($totalDue, 2) }} ৳</td>
                        <td></td>
                        <td></td>
                    </tr>
                @endforeach

                <tr>
                    <td style="height: 15px">{{ $count++ }}</td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height: 15px">{{ $count++ }}</td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height: 15px">{{ $count++ }}</td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height: 15px">{{ $count++ }}</td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height: 15px">{{ $count++ }}</td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height: 15px">{{ $count++ }}</td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height: 15px">{{ $count++ }}</td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height: 15px">{{ $count++ }}</td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height: 15px">{{ $count++ }}</td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height: 15px">{{ $count++ }}</td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @elseif ($customer->purchases->count() <= 3)
                @foreach ($customer->purchases as $purchase)
                    @php
                        $product = $purchase->product;
                        $totalPrice = $purchase->net_price;
                        $down = $purchase->down_price;
                        $totalPaid = $purchase->installments->sum('paid_amount');
                        $totalDue = $purchase->installments->sum(fn($i) => $i->amount - $i->paid_amount);
                        $totalDeposit = $totalPaid + $down;
                        $grandTotalPrice += $totalPrice;
                        $grandTotalPaid += $totalPaid;
                        $grandTotalDown += $down;
                        $grandTotalDue += $totalDue;
                    @endphp
                    <tr>
                        <td>{{ $count++ }}</td>
                        <td colspan="2">{{ $product->product_name }}</td>
                        <td>{{ number_format($totalPrice, 2) }} ৳</td>
                        <td>{{ number_format($totalPrice, 2) }} ৳</td>
                        <td>{{ \Carbon\Carbon::parse($purchase->created_at)->format('d-m-Y') }}</td>
                        <td>{{ number_format($totalDeposit, 2) }} ৳</td>
                        <td>{{ number_format($totalDeposit, 2) }} ৳</td>
                        <td>{{ number_format($totalDue, 2) }} ৳</td>
                        <td></td>
                        <td></td>
                    </tr>
                @endforeach

                <tr>
                    <td style="height: 15px">{{ $count++ }}</td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height: 15px">{{ $count++ }}</td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height: 15px">{{ $count++ }}</td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height: 15px">{{ $count++ }}</td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height: 15px">{{ $count++ }}</td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height: 15px">{{ $count++ }}</td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height: 15px">{{ $count++ }}</td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height: 15px">{{ $count++ }}</td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height: 15px">{{ $count++ }}</td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @elseif ($customer->purchases->count() <= 4)
                @foreach ($customer->purchases as $purchase)
                    @php
                        $product = $purchase->product;
                        $totalPrice = $purchase->net_price;
                        $down = $purchase->down_price;
                        $totalPaid = $purchase->installments->sum('paid_amount');
                        $totalDue = $purchase->installments->sum(fn($i) => $i->amount - $i->paid_amount);
                        $totalDeposit = $totalPaid + $down;
                        $grandTotalPrice += $totalPrice;
                        $grandTotalPaid += $totalPaid;
                        $grandTotalDown += $down;
                        $grandTotalDue += $totalDue;
                    @endphp
                    <tr>
                        <td>{{ $count++ }}</td>
                        <td colspan="2">{{ $product->product_name }}</td>
                        <td>{{ number_format($totalPrice, 2) }} ৳</td>
                        <td>{{ number_format($totalPrice, 2) }} ৳</td>
                        <td>{{ \Carbon\Carbon::parse($purchase->created_at)->format('d-m-Y') }}</td>
                        <td>{{ number_format($totalDeposit, 2) }} ৳</td>
                        <td>{{ number_format($totalDeposit, 2) }} ৳</td>
                        <td>{{ number_format($totalDue, 2) }} ৳</td>
                        <td></td>
                        <td></td>
                    </tr>
                @endforeach

                <tr>
                    <td style="height: 15px">{{ $count++ }}</td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height: 15px">{{ $count++ }}</td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height: 15px">{{ $count++ }}</td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height: 15px">{{ $count++ }}</td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height: 15px">{{ $count++ }}</td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height: 15px">{{ $count++ }}</td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height: 15px">{{ $count++ }}</td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height: 15px">{{ $count++ }}</td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @elseif ($customer->purchases->count() <= 5)
                @foreach ($customer->purchases as $purchase)
                    @php
                        $product = $purchase->product;
                        $totalPrice = $purchase->net_price;
                        $down = $purchase->down_price;
                        $totalPaid = $purchase->installments->sum('paid_amount');
                        $totalDue = $purchase->installments->sum(fn($i) => $i->amount - $i->paid_amount);
                        $totalDeposit = $totalPaid + $down;
                        $grandTotalPrice += $totalPrice;
                        $grandTotalPaid += $totalPaid;
                        $grandTotalDown += $down;
                        $grandTotalDue += $totalDue;
                    @endphp
                    <tr>
                        <td>{{ $count++ }}</td>
                        <td colspan="2">{{ $product->product_name }}</td>
                        <td>{{ number_format($totalPrice, 2) }} ৳</td>
                        <td>{{ number_format($totalPrice, 2) }} ৳</td>
                        <td>{{ \Carbon\Carbon::parse($purchase->created_at)->format('d-m-Y') }}</td>
                        <td>{{ number_format($totalDeposit, 2) }} ৳</td>
                        <td>{{ number_format($totalDeposit, 2) }} ৳</td>
                        <td>{{ number_format($totalDue, 2) }} ৳</td>
                        <td></td>
                        <td></td>
                    </tr>
                @endforeach

                <tr>
                    <td style="height: 15px">{{ $count++ }}</td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height: 15px">{{ $count++ }}</td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height: 15px">{{ $count++ }}</td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height: 15px">{{ $count++ }}</td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height: 15px">{{ $count++ }}</td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height: 15px">{{ $count++ }}</td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height: 15px">{{ $count++ }}</td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @elseif ($customer->purchases->count() <= 6)
                @foreach ($customer->purchases as $purchase)
                    @php
                        $product = $purchase->product;
                        $totalPrice = $purchase->net_price;
                        $down = $purchase->down_price;
                        $totalPaid = $purchase->installments->sum('paid_amount');
                        $totalDue = $purchase->installments->sum(fn($i) => $i->amount - $i->paid_amount);
                        $totalDeposit = $totalPaid + $down;
                        $grandTotalPrice += $totalPrice;
                        $grandTotalPaid += $totalPaid;
                        $grandTotalDown += $down;
                        $grandTotalDue += $totalDue;
                    @endphp
                    <tr>
                        <td>{{ $count++ }}</td>
                        <td colspan="2">{{ $product->product_name }}</td>
                        <td>{{ number_format($totalPrice, 2) }} ৳</td>
                        <td>{{ number_format($totalPrice, 2) }} ৳</td>
                        <td>{{ \Carbon\Carbon::parse($purchase->created_at)->format('d-m-Y') }}</td>
                        <td>{{ number_format($totalDeposit, 2) }} ৳</td>
                        <td>{{ number_format($totalDeposit, 2) }} ৳</td>
                        <td>{{ number_format($totalDue, 2) }} ৳</td>
                        <td></td>
                        <td></td>
                    </tr>
                @endforeach

                <tr>
                    <td style="height: 15px">{{ $count++ }}</td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height: 15px">{{ $count++ }}</td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height: 15px">{{ $count++ }}</td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height: 15px">{{ $count++ }}</td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height: 15px">{{ $count++ }}</td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height: 15px">{{ $count++ }}</td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @elseif ($customer->purchases->count() <= 7)
                @foreach ($customer->purchases as $purchase)
                    @php
                        $product = $purchase->product;
                        $totalPrice = $purchase->net_price;
                        $down = $purchase->down_price;
                        $totalPaid = $purchase->installments->sum('paid_amount');
                        $totalDue = $purchase->installments->sum(fn($i) => $i->amount - $i->paid_amount);
                        $totalDeposit = $totalPaid + $down;
                        $grandTotalPrice += $totalPrice;
                        $grandTotalPaid += $totalPaid;
                        $grandTotalDown += $down;
                        $grandTotalDue += $totalDue;
                    @endphp
                    <tr>
                        <td>{{ $count++ }}</td>
                        <td colspan="2">{{ $product->product_name }}</td>
                        <td>{{ number_format($totalPrice, 2) }} ৳</td>
                        <td>{{ number_format($totalPrice, 2) }} ৳</td>
                        <td>{{ \Carbon\Carbon::parse($purchase->created_at)->format('d-m-Y') }}</td>
                        <td>{{ number_format($totalDeposit, 2) }} ৳</td>
                        <td>{{ number_format($totalDeposit, 2) }} ৳</td>
                        <td>{{ number_format($totalDue, 2) }} ৳</td>
                        <td></td>
                        <td></td>
                    </tr>
                @endforeach

                <tr>
                    <td style="height: 15px">{{ $count++ }}</td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height: 15px">{{ $count++ }}</td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height: 15px">{{ $count++ }}</td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height: 15px">{{ $count++ }}</td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height: 15px">{{ $count++ }}</td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @elseif ($customer->purchases->count() <= 8)
                @foreach ($customer->purchases as $purchase)
                    @php
                        $product = $purchase->product;
                        $totalPrice = $purchase->net_price;
                        $down = $purchase->down_price;
                        $totalPaid = $purchase->installments->sum('paid_amount');
                        $totalDue = $purchase->installments->sum(fn($i) => $i->amount - $i->paid_amount);
                        $totalDeposit = $totalPaid + $down;
                        $grandTotalPrice += $totalPrice;
                        $grandTotalPaid += $totalPaid;
                        $grandTotalDown += $down;
                        $grandTotalDue += $totalDue;
                    @endphp
                    <tr>
                        <td>{{ $count++ }}</td>
                        <td colspan="2">{{ $product->product_name }}</td>
                        <td>{{ number_format($totalPrice, 2) }} ৳</td>
                        <td>{{ number_format($totalPrice, 2) }} ৳</td>
                        <td>{{ \Carbon\Carbon::parse($purchase->created_at)->format('d-m-Y') }}</td>
                        <td>{{ number_format($totalDeposit, 2) }} ৳</td>
                        <td>{{ number_format($totalDeposit, 2) }} ৳</td>
                        <td>{{ number_format($totalDue, 2) }} ৳</td>
                        <td></td>
                        <td></td>
                    </tr>
                @endforeach

                <tr>
                    <td style="height: 15px">{{ $count++ }}</td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height: 15px">{{ $count++ }}</td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height: 15px">{{ $count++ }}</td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height: 15px">{{ $count++ }}</td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @elseif ($customer->purchases->count() <= 9)
                @foreach ($customer->purchases as $purchase)
                    @php
                        $product = $purchase->product;
                        $totalPrice = $purchase->net_price;
                        $down = $purchase->down_price;
                        $totalPaid = $purchase->installments->sum('paid_amount');
                        $totalDue = $purchase->installments->sum(fn($i) => $i->amount - $i->paid_amount);
                        $totalDeposit = $totalPaid + $down;
                        $grandTotalPrice += $totalPrice;
                        $grandTotalPaid += $totalPaid;
                        $grandTotalDown += $down;
                        $grandTotalDue += $totalDue;
                    @endphp
                    <tr>
                        <td>{{ $count++ }}</td>
                        <td colspan="2">{{ $product->product_name }}</td>
                        <td>{{ number_format($totalPrice, 2) }} ৳</td>
                        <td>{{ number_format($totalPrice, 2) }} ৳</td>
                        <td>{{ \Carbon\Carbon::parse($purchase->created_at)->format('d-m-Y') }}</td>
                        <td>{{ number_format($totalDeposit, 2) }} ৳</td>
                        <td>{{ number_format($totalDeposit, 2) }} ৳</td>
                        <td>{{ number_format($totalDue, 2) }} ৳</td>
                        <td></td>
                        <td></td>
                    </tr>
                @endforeach

                <tr>
                    <td style="height: 15px">{{ $count++ }}</td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height: 15px">{{ $count++ }}</td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height: 15px">{{ $count++ }}</td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @elseif ($customer->purchases->count() <= 10)
                @foreach ($customer->purchases as $purchase)
                    @php
                        $product = $purchase->product;
                        $totalPrice = $purchase->net_price;
                        $down = $purchase->down_price;
                        $totalPaid = $purchase->installments->sum('paid_amount');
                        $totalDue = $purchase->installments->sum(fn($i) => $i->amount - $i->paid_amount);
                        $totalDeposit = $totalPaid + $down;
                        $grandTotalPrice += $totalPrice;
                        $grandTotalPaid += $totalPaid;
                        $grandTotalDown += $down;
                        $grandTotalDue += $totalDue;
                    @endphp
                    <tr>
                        <td>{{ $count++ }}</td>
                        <td colspan="2">{{ $product->product_name }}</td>
                        <td>{{ number_format($totalPrice, 2) }} ৳</td>
                        <td>{{ number_format($totalPrice, 2) }} ৳</td>
                        <td>{{ \Carbon\Carbon::parse($purchase->created_at)->format('d-m-Y') }}</td>
                        <td>{{ number_format($totalDeposit, 2) }} ৳</td>
                        <td>{{ number_format($totalDeposit, 2) }} ৳</td>
                        <td>{{ number_format($totalDue, 2) }} ৳</td>
                        <td></td>
                        <td></td>
                    </tr>
                @endforeach

                <tr>
                    <td style="height: 15px">{{ $count++ }}</td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height: 15px">{{ $count++ }}</td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @elseif ($customer->purchases->count() <= 11)
                @foreach ($customer->purchases as $purchase)
                    @php
                        $product = $purchase->product;
                        $totalPrice = $purchase->net_price;
                        $down = $purchase->down_price;
                        $totalPaid = $purchase->installments->sum('paid_amount');
                        $totalDue = $purchase->installments->sum(fn($i) => $i->amount - $i->paid_amount);
                        $totalDeposit = $totalPaid + $down;
                        $grandTotalPrice += $totalPrice;
                        $grandTotalPaid += $totalPaid;
                        $grandTotalDown += $down;
                        $grandTotalDue += $totalDue;
                    @endphp
                    <tr>
                        <td>{{ $count++ }}</td>
                        <td colspan="2">{{ $product->product_name }}</td>
                        <td>{{ number_format($totalPrice, 2) }} ৳</td>
                        <td>{{ number_format($totalPrice, 2) }} ৳</td>
                        <td>{{ \Carbon\Carbon::parse($purchase->created_at)->format('d-m-Y') }}</td>
                        <td>{{ number_format($totalDeposit, 2) }} ৳</td>
                        <td>{{ number_format($totalDeposit, 2) }} ৳</td>
                        <td>{{ number_format($totalDue, 2) }} ৳</td>
                        <td></td>
                        <td></td>
                    </tr>
                @endforeach

                <tr>
                    <td style="height: 15px">{{ $count++ }}</td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @else
                <h1>data not found!</h1>

            @endif

            <tr>
                <td colspan="4" style="border: none; padding-top: 1rem;">ক্রেতার স্বাক্ষর..........
                </td>
                <td colspan="6" style="text-align: right; padding-top: 1rem; border: none;">
                    বিক্রেতার স্বাক্ষর...........
                </td>
            </tr>
        </tbody>
    </table>
</div>


@endif




</div>

</body>


</html>
