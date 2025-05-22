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

    foreach ($request->payments as $purchaseId => $amount) {
        $amount = floatval($amount);
        if ($amount <= 0) continue;

        $purchase = Purchase::findOrFail($purchaseId);

        // Create a standalone payment record (not linked to installment)
        Payment::create([
            'purchase_id' => $purchase->id, // You must add this field to your payments table
            'amount' => $amount,
            'paid_at' => now(),
        ]);

        $purchase->paid_amount += $amount;
        $purchase->save();

        $paymentHistory[] = [
            'purchase_id' => $purchaseId,
            'total_paid' => $amount,
            'date' => now()->format('d-m-Y'),
        ];
    }

    return redirect()->to(url("/customers/{$customer->id}/emi-plans"))
        ->with('success', 'Payment added successfully!')
        ->with('paymentHistory', $paymentHistory);
}

}
