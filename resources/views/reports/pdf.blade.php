@extends('layouts.app')

@section('content')
    <style>
         body {
            font-family: 'solaimanlipi', sans-serif;
            font-size: 14px;
            color: #333;
            background: #f2f2f2;
            margin: 0;
            padding: 0;
        }

    </style>

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
                <img src="{{ asset($customer->customer_image)}}" alt="customer logo" width="100%">
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
                <div class="signature-section">
                    <div class="signature">
                        ___________________________<br>
                        ক্রেতার স্বাক্ষর
                    </div>
                   
                </div>

            </div>
            <div class="col-md-6">
                <div class="signature">
                    ___________________________<br>
                    বিক্রেতার স্বাক্ষর
                </div>
            </div>
        </div>
    </div>
@endsection