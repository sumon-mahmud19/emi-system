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
     public function __construct()
    {
        $this->middleware(['permission:installment-show|installment-show|installment-edit|installment-delete'], ['only' => ['index', 'show']]);
        $this->middleware(['permission:installment-show'], ['only' => ['show', 'store']]);
        $this->middleware(['permission:installment-edit'], ['only' => ['edit', 'update']]);
        $this->middleware(['permission:installment-delete'], ['only' => ['destroy']]);
    }


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

    
    return redirect()->route('purchases.index')->with('success', 'Installment Completed Successfully!');
        
    }
}
