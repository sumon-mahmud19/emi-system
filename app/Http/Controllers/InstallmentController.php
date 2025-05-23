<?php

namespace App\Http\Controllers;

use App\Models\InstallmentPayment;
use App\Models\Payment;
use App\Models\Purchase;
use Illuminate\Http\Request;

class InstallmentController extends Controller
{

   public function payMultiple(Request $request)
{
    $request->validate([
        'customer_id' => 'required|exists:customers,id',
        'payments' => 'required|array',
    ]);

    $customerId = $request->customer_id;

    foreach ($request->payments as $purchaseId => $amount) {
        $amount = floatval($amount);
        if ($amount <= 0) continue;

        $purchase = Purchase::with(['installments' => function ($q) {
            $q->orderBy('due_date');
        }, 'product'])->findOrFail($purchaseId);

        $productId = $purchase->product_id;
        $originalAmount = $amount;

        // Get the first unpaid or partial installment
        $installment = $purchase->installments->first(function ($inst) {
            return $inst->status != 'paid';
        });

        if (!$installment) continue;

        $due = $installment->amount - $installment->paid_amount;
        $installment->paid_amount += $amount;

        // Update status
        if ($installment->paid_amount >= $installment->amount) {
            $installment->status = 'paid';
        } elseif ($installment->paid_amount > 0) {
            $installment->status = 'partial';
        }

        $installment->save();

        // Log the full payment to installment_payments table
        InstallmentPayment::create([
            'installment_id' => $installment->id,
            'amount' => $originalAmount,
            'paid_at' => now(),
        ]);

        // Optionally mark the whole purchase as paid
        $unpaid = $purchase->installments()->where('status', '!=', 'paid')->count();
        if ($unpaid === 0) {
            $purchase->status = 'paid';
            $purchase->save();
        }
    }

    return back()->with('success', 'Payments submitted successfully!');
}

}
