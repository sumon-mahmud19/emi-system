<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Installment;
use App\Models\Invoice;
use App\Models\Location;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
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
        $purchases = Purchase::with(['customer', 'product', 'model'])->get();
        $totalPurchases = $purchases->count();
        return view('purchases.index', compact('purchases', 'totalPurchases'));
    }

    public function create()
    {
        $customers = Customer::all();
        $products = Product::all();
        $locations = Location::all();
        return view('purchases.create', compact('customers', 'products', 'locations'));
    }

    public function getModels($productId)
    {
        $product = Product::findOrFail($productId);
        $models = $product->models; // Assumes relationship 'models' exists in Product model
        return response()->json($models);
    }

    // Autocomplete customer search for Select2
    public function autocomplete(Request $request)
    {
        $search = $request->get('q');

        $customers = Customer::select('id', 'customer_name', 'customer_phone')
            ->where('customer_name', 'LIKE', "%{$search}%")
            ->orWhere('customer_phone', 'LIKE', "%{$search}%")
            ->limit(10)
            ->get();

        $results = [];

        foreach ($customers as $customer) {
            $results[] = [
                'id' => $customer->id,
                'customer_name' => $customer->customer_name,
                'customer_phone' => $customer->customer_phone,
            ];
        }

        return response()->json($results);
    }

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
            return back()->withErrors(['down_price' => 'Down payment cannot exceed total price.'])->withInput();
        }

        DB::beginTransaction();

        try {
            $totalDue = $request->net_price - $request->down_price;

            $purchase = Purchase::create([
                'customer_id' => $request->customer_id,
                'product_id'  => $request->product_id,
                'model_id'    => $request->model_id,
                'sales_price' => $request->net_price,
                'down_price'  => $request->down_price,
                'net_price'   => $request->net_price,
                'emi_plan'    => $request->emi_plan,
            ]);

            // Calculate EMI installment amounts evenly with rounding
            $rawEmi = $totalDue / $request->emi_plan;
            $emiAmount = floor($rawEmi);
            $decimalDiff = $totalDue - ($emiAmount * $request->emi_plan);

            $installments = [];

            for ($i = 0; $i < $request->emi_plan; $i++) {
                $amount = $emiAmount;
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

            DB::commit();

            return $this->generateInvoice($purchase, $installments);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong: ' . $e->getMessage())->withInput();
        }
    }

    private function generateInvoice($purchase, $installments)
    {
        $data = [
            'purchase'     => $purchase,
            'installments' => $installments,
            'customer'     => $purchase->customer,
            'product'      => $purchase->product,
        ];

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
        return $mpdf->Output('Roman_Emi_Invoice.pdf', 'I');
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
            'product_id'  => 'required|exists:products,id',
            'model_id'    => 'required|exists:models,id',
            'net_price'   => 'required|numeric|min:0',
            'down_price'  => 'required|numeric|min:0',
            'emi_plan'    => 'required|integer|min:1',
        ]);

        $purchase->update($request->only([
            'customer_id',
            'product_id',
            'model_id',
            'net_price',
            'down_price',
            'emi_plan'
        ]));

        return redirect()->route('purchases.index')->with('success', 'Purchase updated successfully.');
    }

    public function destroy(Purchase $purchase)
    {
        $purchase->delete();
        return redirect()->route('purchases.index')->with('success', 'Purchase deleted successfully.');
    }
}
