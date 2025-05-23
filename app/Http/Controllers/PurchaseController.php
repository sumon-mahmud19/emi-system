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

        $data = [];

        if ($request->filled('q')) {
            $data = Customer::select("customer_name", "id")
                ->where('customer_name', 'LIKE', '%' . $request->get('q') . '%')
                ->take(10)
                ->get();
        }

        return response()->json($data);
    }


    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'product_id'  => 'required|exists:products,id',
            'model_id'    => 'required|exists:models,id',
            'net_price'   => 'required|numeric|min:0',
            'down_price'  => 'required|numeric|min:0',
            'emi_plan'    => 'required|integer|min:1',
        ]);

        if ($request->down_price > $request->net_price) {
            return back()->withErrors(['down_price' => 'Down payment cannot exceed total price.']);
        }

        // Calculate total due after down payment
        $totalDue = $request->net_price - $request->down_price;

        // Create purchase record
        $purchase = Purchase::create([
            'customer_id' => $request->customer_id,
            'product_id'  => $request->product_id,
            'model_id'    => $request->model_id,
            'sales_price' => $request->net_price,
            'down_price'  => $request->down_price,
            'net_price'   => $request->net_price,
            'emi_plan'    => $request->emi_plan,
        ]);

        // === Installment generation based on totalDue ===

        $rawEmi = $totalDue / $request->emi_plan;
        $emiAmount = floor($rawEmi); // base amount
        $decimalDiff = $totalDue - ($emiAmount * $request->emi_plan);

        $installments = [];

        for ($i = 0; $i < $request->emi_plan; $i++) {
            $amount = $emiAmount;

            // Distribute the remaining difference (distribute 1 extra unit to first few)
            if ($i < round($decimalDiff)) {
                $amount += 1;
            }

            $installments[] = Installment::create([
                'customer_id' => $purchase->customer_id,
                'product_id'  => $purchase->product_id,
                'purchase_id' => $purchase->id,
                'amount'      => $amount,
                'status'      => 'pending',
                'due_date'    => Carbon::now()->addMonths($i + 1)->startOfMonth(),
            ]);
        }

        // === PDF invoice generation ===

        $invoices = Invoice::all();
        $data = [
            'invoices'     => $invoices,
            'purchase'     => $purchase,
            'installments' => $installments,
            'customer'     => $purchase->customer,
            'product'      => $purchase->product,
        ];

        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];
        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];
        $path = public_path('fonts');

        $mpdf = new \Mpdf\Mpdf([
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
