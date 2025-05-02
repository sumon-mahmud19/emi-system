<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Installment;
use App\Models\Location;
use App\Models\Product;
use App\Models\ProductModel;
use App\Models\Purchase;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PurchaseController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:purchase-list|purchase-create|purchase-edit|purchase-delete'], ['only' => ['index', 'show']]);
        $this->middleware(['permission:purchase-create'], ['only' => ['create', 'store']]);
        $this->middleware(['permission:purchase-edit'], ['only' => ['edit', 'update']]);
        $this->middleware(['permission:purchase-delete'], ['only' => ['destroy']]);
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $purchases = Purchase::all();
        return view('purchases.index', compact('purchases'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::all();
        $products = Product::all();
        $locations = Location::all();
        return view('purchases.create', compact('customers', 'products', 'locations'));
    }

    public function getModels($productId)
    {
        // Get the product
        $product = Product::findOrFail($productId);

        // Fetch the models associated with the selected product
        $models = $product->models;

        // Return the models as a JSON response
        return response()->json($models);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id'    => 'required|exists:customers,id',
            'product_id'     => 'required|exists:products,id',
            'model_id'       => 'required',
            'total_price'    => 'required|numeric',
            'sales_price'    => 'required|numeric',
            'down_payment'   => 'required|numeric',
            'emi_plan'       => 'required|integer',
        ]);

        // Store the purchase
        $purchase = Purchase::create([
            'customer_id' => $request->customer_id,
            'product_id' => $request->product_id,
            'model_id' => $request->model_id,
            'total_price' => $request->total_price,
            'sales_price' => $request->sales_price,
            'down_payment' => $request->down_payment,
            'emi_plan' => $request->emi_plan,
        ]);


        // Calculate EMI amount: (Total price - Down payment) / EMI plan duration
        $netAmount = $purchase->total_price - $purchase->down_payment;
        $rawEmiAmount = $netAmount / $purchase->emi_plan;

        // Round EMI according to rule:
        // - If decimal part >= 0.5 → round up
        // - Else → floor it
        $baseEmiAmount = floor($rawEmiAmount);
        $decimalPart = $rawEmiAmount - $baseEmiAmount;

        $emiAmount = ($decimalPart >= 0.5) ? $baseEmiAmount + 1 : $baseEmiAmount;

        // Now create installments
        $installments = [];

        for ($i = 0; $i < $purchase->emi_plan; $i++) {
            $installments[] = Installment::create([
                'customer_id' => $purchase->customer_id,
                'product_id' => $purchase->product_id,
                'purchase_id' => $purchase->id,
                'amount' => $emiAmount,
                'status' => 'pending',
                'due_date' => Carbon::now()->addMonths($i + 1)->startOfMonth(), // Installments start from next month
            ]);
        }

        // Optionally adjust the last installment to fix any rounding difference
        $totalInstallmentSum = $emiAmount * $purchase->emi_plan;
        $adjustment = $totalInstallmentSum - $netAmount;

        if ($adjustment != 0) {
            // Reduce extra amount from the last installment
            $lastInstallment = end($installments);
            $lastInstallment->amount = $lastInstallment->amount - $adjustment;
            $lastInstallment->save();
        }
        // return $data;


        // Prepare data to be passed to the view for PDF generation

        // Return prepared data
        $data = [
            'purchase' => $purchase,
            'emiAmount' => $emiAmount,
            'installments' => $installments,
            'customer' => $purchase->customer, // Load the customer relationship
            'product' => $purchase->product,   // Load the product relationship
        ];

        // Generate PDF from the view and return as a download
        $pdf = PDF::loadView('reports.pdf', $data)
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isPhpEnabled', true);
        return $pdf->stream('invoice.pdf');
    }



    /**
     * Display the specified resource.
     */
    public function show(Purchase $purchase)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Purchase $purchase)
    {
        $customers = Customer::all();
        $products = Product::all();
        $locations = Location::all();
        return view('purchases.edit', compact('purchase', 'customers', 'products', 'locations'));
    }

    public function update(Request $request, Purchase $purchase)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'product_id' => 'required|exists:products,id',
            'total_price' => 'required|numeric',
            'down_payment' => 'required|numeric',
            'emi_plan' => 'required|integer|min:1'
        ]);

        $purchase->update($request->all());

        return redirect()->route('purchases.index')->with('success', 'Purchase updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Purchase $purchase)
    {
        $purchase->delete();
        return redirect()->route('purchases.index')->with('success', 'Purchase deleted successfully.');
    }
}
