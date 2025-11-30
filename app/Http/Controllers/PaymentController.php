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

    public function pay(Request $request)
    {

        $request->validate([
            'installment_id' => $request->installment_id,
            'amount' => $request->amount,
            'paid_at' => $request->paid_at,

        ]);

        return $request->all();

        // $payment = new Payment();
        // $payment->installment_id = $request->installment_id;
        // $payment->amount = $request->amount;
        // $payment->paid_at = now(); // Set the payment date to the current time
        // $payment->save(); // Save the payment



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
