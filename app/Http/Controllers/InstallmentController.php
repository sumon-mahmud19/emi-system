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
    $request->validate([
        'customer_id' => 'required|exists:customers,id',
        'payments' => 'required|array',
    ]);

    foreach ($request->payments as $purchaseId => $amount) {
        $amount = floatval($amount);

        if ($amount <= 0) {
            continue;
        }

        $purchase = Purchase::with(['installments' => function ($q) {
            $q->orderBy('due_date');
        }])->findOrFail($purchaseId);

        foreach ($purchase->installments as $installment) {
            $due = $installment->amount - $installment->paid_amount;

            if ($due <= 0) continue;

            $payNow = min($amount, $due);

            $installment->paid_amount += $payNow;

            // Update status
            if ($installment->paid_amount >= $installment->amount) {
                $installment->status = 'paid';
            } elseif ($installment->paid_amount > 0) {
                $installment->status = 'partial';
            }

            $installment->save();

            // Record payment
            Payment::create([
                'installment_id' => $installment->id,
                'amount' => $payNow,
                'paid_at' => now(),
            ]);

            $amount -= $payNow;

            if ($amount <= 0) break;
        }

        // Check if all installments under this purchase are paid
        $unpaidCount = $purchase->installments()->where('status', '!=', 'paid')->count();
        if ($unpaidCount === 0) {
            $purchase->status = 'paid';
            $purchase->save();
        }
    }

    return back()->with('success', 'Payments submitted successfully!');
}


}
