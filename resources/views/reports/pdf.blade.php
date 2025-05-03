<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Billing Invoice - Webjourney</title>

    <!-- Google Fonts for Bangla Support -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: 'Noto Sans Bengali', sans-serif;
            line-height: 26px;
            font-size: 15px;
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
            margin-bottom: 10px;
        }
        .custom--table thead {
            font-weight: 700;
            background: inherit;
            color: inherit;
            font-size: 16px;
        }
        .custom--table thead tr {
            border-top: 2px solid #ddd;
            border-bottom: 2px solid #ddd;
            text-align: left;
        }
        .custom--table thead tr th {
            text-align: left;
            font-size: 16px;
            padding: 10px 0;
        }
        .custom--table tbody tr td {
            font-size: 14px;
            line-height: 18px;
            vertical-align: top;
            padding: 8px 0;
        }
        .custom--table tbody .table_footer_row {
            border-top: 2px solid #ddd;
        }
        .invoice-area {
            padding: 10px 0;
        }
        .invoice-wrapper {
            max-width: 650px;
            margin: 0 auto;
            box-shadow: 0 0 10px #f3f3f3;
            padding: 20px;
        }
        .invoice-title {
            font-size: 25px;
            font-weight: 700;
            text-align: center;
        }
        .invoice-details-flex {
            display: flex;
            justify-content: space-between;
            gap: 24px;
            flex-wrap: wrap;
        }
        .invoice-details-title {
            font-size: 18px;
            font-weight: 700;
            color: #333;
        }
        .details-list {
            list-style: none;
            padding: 0;
            margin-top: 10px;
        }
        .details-list .list {
            font-size: 14px;
            color: #666;
            line-height: 22px;
        }
        .data-span {
            font-weight: 500;
            display: inline-block;
            width: 100px;
        }
        footer h3 {
            text-align: center;
            margin-top: 40px;
        }
    </style>
</head>
<body>

<!-- Invoice area Starts -->
<div class="invoice-area">
    <div class="invoice-wrapper">

        <div class="invoice-header">
            <h1 class="invoice-title">ইনভয়েস শিরোনাম - WebJourney</h1>
        </div>

        <div class="invoice-details">
            <div class="invoice-details-flex">
                <div class="invoice-single-details">
                    <h2 class="invoice-details-title">বিল প্রাপক:</h2>
                    <ul class="details-list">
                        <li class="list">{{ $customer->customer_name}}</li>
                        <li class="list"><a href="#">nazmul@gmail.com</a></li>
                        <li class="list"><a href="tel:0167846483">0167846483</a></li>
                    </ul>
                </div>
                <div class="invoice-single-details">
                    <h4 class="invoice-details-title">প্রেরণ ঠিকানা:</h4>
                    <ul class="details-list">
                        <li class="list"><strong>শহর:</strong> ঢাকা</li>
                        <li class="list"><strong>এলাকা:</strong> ধানমন্ডি</li>
                        <li class="list"><strong>ঠিকানা:</strong> পশ্চিম পান্থপথ, ধানমন্ডি</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="item-description">
            <h5 class="table-title">সেবার বিবরণ</h5>
            <table class="custom--table">
                <thead>
                    <tr>
                        <th>সেবা</th>
                        <th>ইউনিট মূল্য</th>
                        <th>পরিমাণ</th>
                        <th>মোট</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>বাড়ি পরিষ্কার</td>
                        <td>৳১০</td>
                        <td>৩</td>
                        <td>৳৩০</td>
                    </tr>
                    <tr>
                        <td>গাড়ি পরিষ্কার</td>
                        <td>৳২০</td>
                        <td>২</td>
                        <td>৳৪০</td>
                    </tr>
                    <tr class="table_footer_row">
                        <td colspan="3"><strong>প্যাকেজ ফি</strong></td>
                        <td><strong>৳৭০</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="item-description">
            <h5 class="table-title">অর্ডার বিবরণ</h5>
            <table class="custom--table">
                <thead>
                    <tr>
                        <th>ক্রেতার তথ্য</th>
                        <th>তারিখ ও সময়</th>
                        <th>মূল্য বিবরণ</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <span class="data-span">নাম:</span>নাজমুল হক<br>
                            <span class="data-span">ইমেইল:</span>nazmul@gmail.com<br>
                            <span class="data-span">ফোন:</span><a href="tel:01678985958">01678985958</a><br>
                            <span class="data-span">ঠিকানা:</span>পশ্চিম পান্থপথ, ধানমন্ডি
                        </td>
                        <td>
                            ৩০-০৯-২০২২<br>
                            শুক্রবার, রাত ১০টা
                        </td>
                        <td>
                            <span class="data-span">প্যাকেজ ফি:</span>৳৭০<br>
                            <span class="data-span">সাবটোটাল:</span>৳৭০<br>
                            <span class="data-span">ট্যাক্স:</span>৳১০<br>
                            <span class="data-span">মোট:</span>৳৮০<br>
                            <span class="data-span">পেমেন্ট স্ট্যাটাস:</span>বাকি আছে
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <footer>
            <h3>© 2023 সকল অধিকার সংরক্ষিত</h3>
        </footer>

    </div>
</div>
<!-- Invoice area end -->

</body>
</html>
