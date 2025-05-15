<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Installment;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mpdf\Mpdf;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;

class InstallmentController extends Controller
{

    public function payMultiple(Request $request)
    {
        $customer = Customer::findOrFail($request->customer_id);

        $paymentHistory = [];
        $paidPurchases = [];

        foreach ($request->payments as $purchaseId => $amount) {
            $amount = floatval($amount);
            if ($amount <= 0) continue;

            $purchase = Purchase::findOrFail($purchaseId);
            $installments = $purchase->installments()->where('status', '!=', 'paid')->orderBy('id')->get();

            $totalDue = 0;
            $totalPaid = 0;

            foreach ($installments as $installment) {
                $due = $installment->amount - $installment->paid_amount;
                $totalDue += $due;

                if ($amount <= 0) break;

                $payNow = min($amount, $due);
                $installment->paid_amount += $payNow;

                if ($installment->paid_amount >= $installment->amount) {
                    $installment->status = 'paid';
                } else {
                    $installment->status = 'partial';
                }

                $installment->save();

                Payment::create([
                    'installment_id' => $installment->id,
                    'amount' => $payNow,
                    'paid_at' => now(),
                ]);

                $totalPaid += $payNow;
                $amount -= $payNow;
            }

            $totalInstallments = $purchase->installments()->count();
            $paidInstallments = $purchase->installments()->where('status', 'paid')->count();

            if ($totalInstallments == $paidInstallments) {
                $purchase->status = 'paid';
                $purchase->save();
            }

            // Track successful purchase for PDF
            $paidPurchases[] = $purchase;

            $paymentHistory[] = [
                'purchase_id' => $purchaseId,
                'total_due' => $totalDue,
                'total_paid' => $totalPaid,
                'status' => $purchase->status,
            ];
        }

        // ✅ Prepare Data for PDF
        $data = [
            'customer' => $customer,
            'paidPurchases' => $paidPurchases,
        ];

        // ✅ mPDF Configuration
        $defaultConfig = (new ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];
        $defaultFontConfig = (new FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];
        $path = public_path('fonts');

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'fontDir' => array_merge($fontDirs, [$path]),
            'fontdata' => $fontData + [
                'solaimanlipi' => [
                    'R' => 'SolaimanLipi.ttf',
                    'useOTL' => 0xFF,
                ],
            ],
            'default_font' => 'solaimanlipi'
        ]);

        $html = view('reports.payment_receipt', $data)->render(); // Create this Blade file
        $mpdf->WriteHTML($html);

        return $mpdf->Output('PaymentReceipt.pdf', 'I'); // Show in browser
    }
}
