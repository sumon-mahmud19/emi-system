<?php

namespace App\Http\Controllers;

use App\Models\Installment;
use App\Models\Purchase;
use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{

    public function __construct()
    {
        $this->middleware(['permission:report-list|report-create|report-edit|report-delete'], ['only' => ['index', 'show']]);
        $this->middleware(['permission:report-create'], ['only' => ['create', 'store']]);
        $this->middleware(['permission:report-edit'], ['only' => ['edit', 'update']]);
        $this->middleware(['permission:report-delete'], ['only' => ['destroy']]);
    }



    public function monthlyReport(Request $request)
    {
        $filter = $request->input('filter', 'month'); // 'month', 'week', or 'year'
        $date = $request->input('date', now()->format('Y-m'));

        if ($filter === 'month') {
            $start = Carbon::createFromFormat('Y-m', $date)->startOfMonth();
            $end = Carbon::createFromFormat('Y-m', $date)->endOfMonth();
        } elseif ($filter === 'week') {
            $start = Carbon::parse($date)->startOfWeek();
            $end = Carbon::parse($date)->endOfWeek();
        } else { // year
            $start = Carbon::createFromFormat('Y', $date)->startOfYear();
            $end = Carbon::createFromFormat('Y', $date)->endOfYear();
        }

        $purchases = Purchase::with('product', 'installments')
            ->whereBetween('created_at', [$start, $end])
            ->paginate(10);

        $totalPurchase = $purchases->sum('net_price');

        $totalPaid = $purchases->sum(function ($purchase) {
            $down = $purchase->down_price ?? 0;
            $installmentPaid = $purchase->installments->sum('paid_amount');
            return $down + $installmentPaid;
        });

        $totalDue = $purchases->sum(function ($purchase) {
            return $purchase->installments->sum(fn($i) => $i->amount - $i->paid_amount);
        });


        $totalSales = $purchases->sum('net_price');
         return $totalSales;

        $netSales = $purchases->sum(function ($purchase) {
            return $purchase->net_price ?? 0;
        });

        $profit = $netSales - $totalSales;

        return view('reports.monthly', compact('totalPurchase', 'totalPaid', 'totalDue', 'profit', 'purchases', 'filter', 'date'));
    }
}
