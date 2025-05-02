<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Installment;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InstallmentController extends Controller
{

    public function payMultiple(Request $request)
    {
        $customer = Customer::findOrFail($request->customer_id);

        // Initialize an array to track payments
        $paymentHistory = [];

        // Process each purchase and its installments
        foreach ($request->payments as $purchaseId => $amount) {
            // Ensure amount is valid
            $amount = floatval($amount);
            if ($amount <= 0) continue; // Skip if the amount is invalid

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

                // Update status based on payment amount
                if ($installment->paid_amount >= $installment->amount) {
                    $installment->status = 'paid';
                } else {
                    $installment->status = 'partial';
                }

                // Save the installment update
                $installment->save();

                // Log the payment history
                Payment::create([
                    'installment_id' => $installment->id,
                    'amount' => $payNow,
                    'paid_at' => now(),
                ]);

                $totalPaid += $payNow;
                $amount -= $payNow;
            }

            // Mark the purchase as fully paid if all installments are paid
            $totalInstallments = $purchase->installments()->count();
            $paidInstallments = $purchase->installments()->where('status', 'paid')->count();

            if ($totalInstallments == $paidInstallments) {
                $purchase->status = 'paid';
                $purchase->save();
            }

            // Track payment history for the purchase
            $paymentHistory[] = [
                'purchase_id' => $purchaseId,
                'total_due' => $totalDue,
                'total_paid' => $totalPaid,
                'status' => $purchase->status,
            ];
        }

        return redirect()->route('customers.emi_plans', $customer->id)
        ->with('success', 'Payments successfully processed.')
        ->with('paymentHistory', $paymentHistory);
    }
    
}
