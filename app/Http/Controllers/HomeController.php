<?php

namespace App\Http\Controllers;

use App\Models\Installment;
use App\Models\Purchase;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Get all purchases with relationships
    $purchases = Purchase::with('installments')->get();

    // Get all installments
    $installments = Installment::all();

    // Calculate totals
    $totalPurchase = $purchases->sum('total_price');
    
    $totalPaid = $installments->where('status', 'paid')->sum('amount');
    $totalDue = $totalPurchase - $totalPaid;

    // Calculate total profit (sales_price - total_price for each purchase)
    $totalProfit = $purchases->sum(function ($purchase) {
        return $purchase->sales_price - $purchase->total_price;
    });

    return view('home', compact(
        'totalPurchase',
        'totalPaid',
        'totalDue',
        'totalProfit'
    ));

    }
}
