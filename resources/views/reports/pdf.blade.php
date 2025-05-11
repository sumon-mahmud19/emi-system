
        * {
            font-family: 'solaimanlipi', sans-serif;
        }

       <!DOCTYPE html>
       <html lang="en">
       <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Roman Emi Invoice</title>
        <style>
            *{
                font-family: 'solaimanlipi', sans-serif !important;
                background-color: red;
            }
        </style>
       </head>
       <body>
        
        <table>
            <tr>
                <th>Customer Info:</th>
                <th>
                    Customer Image
                </th>
            </tr>

            <tr>
                <td>
                    <p><strong>ক্রেতার নাম:</strong> {{ $customer->customer_name }}</p>
                    <p><strong>মোবাইল:</strong> {{ $customer->customer_phone }}</p>
                    <p><strong>ঠিকানা:</strong> {{ $customer->location->name ?? 'N/A' }}</p>
                    <p><strong>পণ্যের নাম:</strong> {{ $product->product_name }}</p>
                </td>
                <td>
                    <img src="{{ $customer->customer_image}}" alt="logo" width="100px">
                </td>
            </tr>

            
        </table>
       </body>
       </html>