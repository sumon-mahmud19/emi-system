<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Installment;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function payMultiple(Request $request)
    {
        $customer = Customer::findOrFail($request->customer_id); // Find the customer

        // Loop through each payment for the purchases
        foreach ($request->payments as $purchaseId => $paymentAmount) {
            if ($paymentAmount > 0) {
                // Find the corresponding installment
                $installment = Installment::where('purchase_id', $purchaseId)
                    ->where('status', '!=', 'paid')
                    ->first(); // Get the first installment with an outstanding balance

                if ($installment) {
                    // Insert the payment into the payments table
                    $payment = new Payment();
                    $payment->installment_id = $installment->id;
                    $payment->amount = $paymentAmount;
                    $payment->paid_at = now(); // Set the payment date to the current time
                    $payment->save(); // Save the payment

                    // Update the installment status to 'paid' if the balance is settled
                    $installment->status = $installment->amount <= $paymentAmount ? 'paid' : 'partial';
                    $installment->save();
                }
            }
        }

        // Redirect back to the customer's EMI plan page
        return redirect()->route('customers.emi_plans', ['id' => $customer->id]);
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        //
    }
}
