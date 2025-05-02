<?php

namespace App\Http\Controllers;

use App\Models\Installment;
use App\Models\Purchase;
use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{

    // public function __construct()
    // {
    //     $this->middleware(['permission:report-list|report-create|report-edit|report-delete'], ['only' => ['index', 'show']]);
    //     $this->middleware(['permission:report-create'], ['only' => ['create', 'store']]);
    //     $this->middleware(['permission:report-edit'], ['only' => ['edit', 'update']]);
    //     $this->middleware(['permission:report-delete'], ['only' => ['destroy']]);
    // }



    public function monthlyReport(Request $request)
    {

        // Get the selected month from the request or default to the current month
        $selectedMonth = $request->input('month', now()->format('Y-m'));

        // Get the start and end dates of the selected month
        $startOfMonth = Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth();
        $endOfMonth = Carbon::createFromFormat('Y-m', $selectedMonth)->endOfMonth();

        // Get purchases for the selected month
        $purchases = Purchase::with('product', 'installments')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->paginate(3);

        // Debugging the total purchase calculation
        $totalPurchase = $purchases->sum('total_price');


        // Sum downpayments and installment payments for total paid
        $totalPaid = $purchases->sum(function ($purchase) {
            $downpayment = $purchase->downpayment ?? 0;
            $installmentPaid = $purchase->installments->sum('paid_amount');
            return $downpayment + $installmentPaid;
        });


        // Calculate total due by subtracting paid amounts from the total installment amount
        $totalDue = $purchases->sum(function ($purchase) {
            return $purchase->installments->sum(fn($installment) => $installment->amount - $installment->paid_amount);
        });


        // Pass data to the view
        return view('reports.monthly', compact('totalPurchase', 'totalPaid', 'totalDue', 'purchases'));
    }
}
