<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Billing Invoice - Webjourney</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;0,900;1,300&display=swap" rel="stylesheet">
</head>
<body>

<!-- Invoice area Starts -->
<div class="invoice-area">
    <div class="invoice-wrapper">
        <div class="invoice-header">
            <h1 class="invoice-title" style="text-align:center;">Invoice Heder - WebJourney</h1>
        </div>
        <div class="invoice-details">
            <div class="invoice-details-flex">
                <div class="invoice-single-details">
                    <h2 class="invoice-details-title">Bill To:</h2>
                    <ul class="details-list">
                        <li class="list">{{ $customer->customer_name}}</li>
                        <li class="list"> <a href="#">nazmul@gmail.com </a> </li>
                        <li class="list"> <a href="#"> 0167846483</a> </li>
                    </ul>
                </div>
                <div class="invoice-single-details">
                    <h4 class="invoice-details-title">Ship To:</h4>
                    <ul class="details-list">
                        <li class="list"> <strong>City: </strong> Dhaka</li>
                        <li class="list"> <strong>Area: </strong>Dhanmondi</li>
                        <li class="list"> <strong>Address: </strong>West Panthapath, Dhanmondi</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="item-description">
            <h5 class="table-title">Include Services</h5>
            <table class="custom--table">
                <thead>
                <tr>
                    <th>Title</th>
                    <th>Unit Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>House Cleaning</td>
                    <td>$10</td>
                    <td>3</td>
                    <td>$30</td>
                </tr>
                <tr>
                    <td>Car Cleaning</td>
                    <td>$20</td>
                    <td>2</td>
                    <td>$40</td>
                </tr>
                <tr class="table_footer_row">
                    <td colspan="3"><strong>Package Fee</strong></td>
                    <td><strong>$70</strong></td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="item-description">
            <div class="table-responsive">
                <h5 class="table-title">Orders Details</h5>
                <table class="custom--table">
                    <thead class="head-bg">
                    <tr>
                        <th>Buyer Details</th>
                        <th>Date & Schedule</th>
                        <th>Amount Details</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>
                            <span class="data-span">Name:</span>Nazmul Hoque<br>
                            <span class="data-span">Email:</span>Nazmul@gmail.com <br>
                            <span class="data-span">Phone: </span>01678985958 <br>
                            <span class="data-span">Address:</span>West Panthapath, Dhanmondi
                        </td>
                        <td>
                            30-9-2022 <br>
                            Fri, 10pm
                        </td>
                        <td>
                            <span class="data-span"> Package Fee:</span>$70 <br>
                            <span class="data-span"> Sub Total:</span>$70 <br>
                            <span class="data-span"> Tax: </span>$10 <br>
                            <span class="data-span"> Total:</span>$80 <br>
                            <span class="data-span">Payment Status: </span>Pending
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <footer>
            <h3 style="text-align: center">
                Copyright @2023
            </h3>
        </footer>

    </div>
</div>

<!-- Invoice area end -->

</body>

</html>