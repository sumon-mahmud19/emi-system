<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>চালান রশিদ - Roman EMi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bangla font -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: 'Noto Sans Bengali', sans-serif;
            font-size: 16px;
            line-height: 28px;
        }

        body {
            background: #f9f9f9;
            padding: 30px;
        }

        .invoice-wrapper {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 20px 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .invoice-header h1 {
            text-align: center;
            font-size: 28px;
            margin-bottom: 20px;
        }

        .invoice-section {
            margin-bottom: 20px;
        }

        .section-title {
            font-weight: bold;
            margin-bottom: 10px;
            font-size: 18px;
        }

        .details-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .details-list li {
            margin-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table thead {
            background-color: #eee;
        }

        table th, table td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
        }

        footer {
            text-align: center;
            margin-top: 30px;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="invoice-wrapper">
    <div class="invoice-header">
        <h1>চালান রশিদ (Invoice)</h1>
    </div>

    <div class="invoice-section">
        <div class="section-title">বিল প্রাপক:</div>
        <ul class="details-list">
            <li>নাম: মোঃ হাসান</li>
            <li>ইমেইল: hasan@example.com</li>
            <li>ফোন: ০১৭৬৩৪১৭০৭১</li>
        </ul>
    </div>

    <div class="invoice-section">
        <div class="section-title">ঠিকানা:</div>
        <ul class="details-list">
            <li>শহর: ঢাকা</li>
            <li>এলাকা: ধানমন্ডি</li>
            <li>ঠিকানা: পশ্চিম পান্থপথ</li>
        </ul>
    </div>

    <div class="invoice-section">
        <div class="section-title">সেবার বিবরণ:</div>
        <table>
            <thead>
                <tr>
                    <th>সেবা</th>
                    <th>মূল্য</th>
                    <th>পরিমাণ</th>
                    <th>মোট</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>ঘর পরিষ্কার</td>
                    <td>৳৫০০</td>
                    <td>২</td>
                    <td>৳১০০০</td>
                </tr>
                <tr>
                    <td>গাড়ি ধোয়া</td>
                    <td>৳৭০০</td>
                    <td>১</td>
                    <td>৳৭০০</td>
                </tr>
                <tr>
                    <td colspan="3"><strong>মোট</strong></td>
                    <td><strong>৳১৭০০</strong></td>
                </tr>
            </tbody>
        </table>
    </div>

    <footer>
        &copy; ২০২৫ - সর্বস্বত্ব সংরক্ষিত।
    </footer>
</div>

</body>
</html>
