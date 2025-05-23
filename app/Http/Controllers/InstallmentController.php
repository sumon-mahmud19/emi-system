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

            $originalAmount = $amount; // Keep track for full record
            $productId = $purchase->product_id;

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

                // Record into original installment_payments table
                $installment->payments()->create([
                    'amount' => $payNow,
                    'paid_at' => now(),
                ]);

                $amount -= $payNow;
                if ($amount <= 0) break;
            }

            // Insert summary in new payments table
            InstallmentPayment::create([
                'installment_id' => $installment->id, // replace with your actual installment ID
                'amount' => $payNow,                  // replace with payment amount
                'paid_at' => now(),                   // or a custom date
            ]);

            // Mark purchase paid if all its installments are paid
            $unpaid = $purchase->installments()->where('status', '!=', 'paid')->count();
            if ($unpaid === 0) {
                $purchase->status = 'paid';
                $purchase->save();
            }
        }

        return back()->with('success', 'Payments submitted successfully!');
    }
}
