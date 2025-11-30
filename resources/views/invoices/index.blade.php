@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Invoices</h2>


    <div class="table-responsive">
        <table class="table table-bordered" id="invoiceTable">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Product</th>
                    <th>Total Price</th>
                    <th>EMI/Month</th>
                    <th>Next EMI</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="invoiceBody">
                @foreach($invoices as $invoice)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $invoice->name }}</td>
                    <td>{{ $invoice->phone }}</td>
                    <td>{{ $invoice->product_name }} ({{ $invoice->product_model }})</td>
                    <td>{{ $invoice->total_price }}</td>
                    <td>{{ $invoice->emi_month }}</td>
                    <td>{{ $invoice->next_emi_amount }}</td>
                    <td>
                        <a href="{{ route('invoices.edit', $invoice->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    </td>
                </tr>
                @endforeach
                
            </tbody>
        </table>

       
    </div>
</div>
@endsection


