<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Installment;
use App\Models\Invoice;
use App\Models\Location;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Mpdf\Mpdf;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;

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

        $invoices = Invoice::all();
        // Return prepared data
        $data = [
            'invoices' => $invoices,
            'purchase' => $purchase,
            'emiAmount' => $emiAmount,
            'installments' => $installments,
            'customer' => $purchase->customer,
            'product' => $purchase->product,
        ];

       
        // Get default configurations
        $defaultConfig = (new ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        // Path to custom fonts
        $path = public_path('/fonts');

        // Create new mPDF instance with custom font settings
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'fontDir' => array_merge($fontDirs, [$path]),
            'fontdata' => $fontData + [
                'solaimanlipi' => [
                    'R' => 'SolaimanLipi.ttf',
                    'useOTL' => 0xFF,
                ],
            ],
            'default_font' => 'solaimanlipi'
        ]);

        // Load HTML view
        $html = view('reports.pdf', $data)->render();

        // Write HTML to PDF
        $mpdf->WriteHTML($html);

        // Output PDF to browser
        return $mpdf->Output('BracApprovalDocument.pdf', 'S');

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
