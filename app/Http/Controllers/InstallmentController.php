<?php

namespace App\Http\Controllers;

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

        $purchase = Purchase::with(['product', 'installments' => function ($q) {
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
                'customer_id'     => $customerId,
                'purchase_id'     => $purchase->id,
                'product_id'      => $purchase->product->id ?? null,
                'installment_id'  => $installment->id,
                'amount'          => $payNow,
                'status'          => $installment->status,
                'paid_at'         => now(),
            ]);

            $amount -= $payNow;

            if ($amount <= 0) break;
        }

        // If all installments are paid, mark purchase as paid
        if ($purchase->installments()->where('status', '!=', 'paid')->count() === 0) {
            $purchase->status = 'paid';
            $purchase->save();
        }
    }

    return back()->with('success', 'Payments submitted successfully!');
}


}
