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

    public function index()
    {
        $purchases = Purchase::all();
        $totalPurchases = Purchase::count();
        return view('purchases.index', compact('purchases', 'totalPurchases'));
    }

    public function create()
    {
        $products = Product::all();
        $locations = Location::all();
        return view('purchases.create', compact('products', 'locations'));
    }


    public function getModels($productId)
    {
        $product = Product::with('models')->find($productId);

        if (!$product) {
            return response()->json([], 404);
        }

        return response()->json($product->models); // Must be a collection of models with model_name
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

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'product_id' => 'required|exists:products,id',
            'model_id' => 'required',
            'sales_price' => 'required|numeric',
            'down_price' => 'required|numeric',
            'net_price' => 'required|numeric',
            'emi_plan' => 'required|integer|min:1',
        ]);

        $purchase = Purchase::create([
            'customer_id' => $request->customer_id,
            'product_id' => $request->product_id,
            'model_id' => $request->model_id,
            'sales_price' => $request->sales_price,
            'down_price' => $request->down_price,
            'net_price' => $request->net_price,
            'emi_plan' => $request->emi_plan,
        ]);

        $totalDue = $purchase->sales_price - $purchase->net_price;

        $rawEmi = $totalDue / $purchase->emi_plan;
        $baseEmi = floor($rawEmi);
        $decimal = $rawEmi - $baseEmi;
        $emiAmount = $decimal >= 0.5 ? $baseEmi + 1 : $baseEmi;

        $installments = [];
        for ($i = 0; $i < $purchase->emi_plan; $i++) {
            $installments[] = Installment::create([
                'customer_id' => $purchase->customer_id,
                'product_id' => $purchase->product_id,
                'purchase_id' => $purchase->id,
                'amount' => $emiAmount,
                'status' => 'pending',
                'due_date' => Carbon::now()->addMonths($i + 1)->startOfMonth(),
            ]);
        }

        // Adjust last installment
        $totalInstallment = $emiAmount * $purchase->emi_plan;
        $adjustment = $totalInstallment - $totalDue;
        if ($adjustment != 0) {
            $last = end($installments);
            $last->amount -= $adjustment;
            $last->save();
        }

        // PDF data
        $data = [
            'purchase' => $purchase,
            'emiAmount' => $emiAmount,
            'installments' => $installments,
            'customer' => $purchase->customer,
            'product' => $purchase->product,
            'invoices' => Invoice::all(),
        ];

        // mPDF config for Bangla font
        $defaultConfig = (new ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'fontDir' => array_merge($fontDirs, [public_path('fonts')]),
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

        return response($mpdf->Output('Roman_Emi_Invoice.pdf', 'S'), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="Roman_Emi_Invoice.pdf"');
    }

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
            'model_id' => 'required',
            'sales_price' => 'required|numeric',
            'down_price' => 'required|numeric',
            'net_price' => 'required|numeric',
            'emi_plan' => 'required|integer|min:1'
        ]);

        $purchase->update([
            'customer_id' => $request->customer_id,
            'product_id' => $request->product_id,
            'model_id' => $request->model_id,
            'sales_price' => $request->sales_price,
            'down_price' => $request->down_price,
            'net_price' => $request->net_price,
            'emi_plan' => $request->emi_plan,
        ]);

        return redirect()->route('purchases.index')->with('success', 'Purchase updated successfully.');
    }

    public function destroy(Purchase $purchase)
    {
        $purchase->delete();
        return redirect()->route('purchases.index')->with('success', 'Purchase deleted successfully.');
    }
}
