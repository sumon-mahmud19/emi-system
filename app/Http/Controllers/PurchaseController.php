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
        $totalPurchases = Purchase::count();
        return view('purchases.index', compact('purchases', 'totalPurchases'));
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


   
public function autocomplete(Request $request)
{
    $term = $request->input('term');

    $customers = Customer::query()
        ->where('customer_name', 'like', '%' . $term . '%')
        ->orWhere('customer_phone', 'like', '%' . $term . '%')
        ->select('id', 'customer_name', 'customer_phone')
        ->limit(10)
        ->get();

    $results = $customers->map(function ($customer) {
        return [
            'id' => $customer->id,
            'customer_name' => $customer->customer_name,
            'customer_phone' => $customer->customer_phone,
        ];
    });

    return response()->json($results);
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
            'sales_price'    => 'required|numeric',
            'down_price'    => 'required|numeric',
            'net_price'    => 'required|numeric',
            'emi_plan'       => 'required|integer|min:1',
        ]);

    
        // Save purchase info
        $purchase = Purchase::create([
            'customer_id' => $request->customer_id,
            'product_id' => $request->product_id,
            'model_id' => $request->model_id,
            'sales_price' => $request->sales_price,
            'down_price' => $request->down_price,
            'net_price' => $request->net_price,
            'emi_plan' => $request->emi_plan,
        ]);

        // Total due = sales_price - cash (no down payment logic anymore)
        $totalDue = $purchase->sales_price - $purchase->total_price;

        // EMI amount calculation
        $rawEmiAmount = $totalDue / $purchase->emi_plan;
        $baseEmi = floor($rawEmiAmount);
        $decimalPart = $rawEmiAmount - $baseEmi;
        $emiAmount = ($decimalPart >= 0.5) ? $baseEmi + 1 : $baseEmi;

        // Generate installments
        $installments = [];
        for ($i = 0; $i < $purchase->emi_plan; $i++) {
            $installments[] = Installment::create([
                'customer_id'  => $purchase->customer_id,
                'product_id'   => $purchase->product_id,
                'purchase_id'  => $purchase->id,
                'amount'       => $emiAmount,
                'status'       => 'pending',
                'due_date'     => Carbon::now()->addMonths($i + 1)->startOfMonth(),
            ]);
        }

        // Adjust the last installment if there's any rounding difference
        $totalInstallmentSum = $emiAmount * $purchase->emi_plan;
        $adjustment = $totalInstallmentSum - $totalDue;

        if ($adjustment !== 0) {
            $lastInstallment = end($installments);
            $lastInstallment->amount -= $adjustment;
            $lastInstallment->save();
        }

        // Invoice + PDF
        $invoices = Invoice::all();
        $data = [
            'invoices' => $invoices,
            'purchase' => $purchase,
            'emiAmount' => $emiAmount,
            'installments' => $installments,
            'customer' => $purchase->customer,
            'product' => $purchase->product,
        ];

        $defaultConfig = (new ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];
        $defaultFontConfig = (new FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];
        $path = public_path('fonts');

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

        $html = view('reports.pdf', $data)->render();
        $mpdf->WriteHTML($html);

        return $mpdf->Output('Roman_Emi_Invoice.pdf', 'I');
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
