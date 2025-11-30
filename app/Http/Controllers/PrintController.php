<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mpdf\Mpdf;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use App\Models\Customer;
use App\Models\InstallmentPayment;

class PrintController extends Controller
{


    public function report($id)
    {
        $customer = Customer::with('purchases.installments')->findOrFail($id);
        // $customer = Customer::findOrFail($id);

    //   return $customer;

        $paymentHistory = InstallmentPayment::with('installment.purchase.product')
            ->whereHas('installment.purchase', function ($query) use ($id) {
                $query->where('id', $id);
            })
            ->orderBy('paid_at', 'desc')
            ->get();


        //    return $paymentHistory;

        $data = [
            'customer' => $customer,
            'paymentHistory' => $paymentHistory,
        ];

        $defaultConfig = (new ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];
        $defaultFontConfig = (new FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];
        $path = public_path('fonts');

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4-L', // 🔹 Landscape mode
            'fontDir' => array_merge($fontDirs, [$path]),
            'fontdata' => $fontData + [
                'solaimanlipi' => [
                    'R' => 'SolaimanLipi.ttf',
                    'useOTL' => 0xFF,
                ],
            ],
            'default_font' => 'solaimanlipi'
        ]);

        $html = view('reports.report-print', compact('customer', 'paymentHistory'))->render();
        $mpdf->WriteHTML($html);

        // 🔹 Show PDF in browser
        return response($mpdf->Output('', 'S'))
            ->header('Content-Type', 'application/pdf');
    }
}
