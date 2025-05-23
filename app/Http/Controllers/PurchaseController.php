<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Installment;
use App\Models\InstallmentPayment;
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
        $customers = Customer::all();
        $products = Product::all();
        $locations = Location::all();
        return view('purchases.create', compact('customers', 'products', 'locations'));
    }

    public function getModels($productId)
    {
        $product = Product::findOrFail($productId);
        $models = $product->models;
        return response()->json($models);
    }

    public function autocomplete(Request $request)
    {
        $data = [];

        if ($request->filled('q')) {
            $data = Customer::select("customer_name")
                ->where('customer_name', 'LIKE', '%' . $request->get('q') . '%')
                ->take(10)
                ->get()
                ->pluck('customer_name'); // Extract only names as plain array
        }

        return response()->json($data);
    }


    public function store(Request $request)
    {
        $request->validate([
            'customer_id'  => 'required|exists:customers,id',
            'product_id'   => 'required|exists:products,id',
            'model_id'     => 'required',
            'sales_price'  => 'required|numeric',
            'down_price'   => 'required|numeric',
            'net_price'    => 'required|numeric',
            'emi_plan'     => 'required|integer|min:1',
        ]);

        $purchase = Purchase::create([
            'customer_id' => $request->customer_id,
            'product_id'  => $request->product_id,
            'model_id'    => $request->model_id,
            'sales_price' => $request->sales_price,
            'down_price'  => $request->down_price,
            'net_price'   => $request->net_price,
            'emi_plan'    => $request->emi_plan,
        ]);

        $totalDue = $purchase->net_price;
        $rawEmiAmount = $totalDue / $purchase->emi_plan;
        $baseEmi = floor($rawEmiAmount);
        $decimalPart = $rawEmiAmount - $baseEmi;
        $emiAmount = ($decimalPart >= 0.5) ? $baseEmi + 1 : $baseEmi;

        $installments = [];
        for ($i = 0; $i < $purchase->emi_plan; $i++) {
            $installments[] = Installment::create([
                'customer_id' => $purchase->customer_id,
                'product_id'  => $purchase->product_id,
                'purchase_id' => $purchase->id,
                'amount'      => $emiAmount,
                'status'      => 'pending',
                'due_date'    => Carbon::now()->addMonths($i + 1)->startOfMonth(),
            ]);
        }

        $totalInstallmentSum = $emiAmount * $purchase->emi_plan;
        $adjustment = $totalInstallmentSum - $totalDue;

        if ($adjustment !== 0) {
            $lastInstallment = end($installments);
            $lastInstallment->amount -= $adjustment;
            $lastInstallment->save();
        }

        // ✅ Apply Down Payment to First Installment
        if ($purchase->down_price > 0 && !empty($installments)) {
            $firstInstallment = $installments[0];
            $firstInstallment->paid_amount += $purchase->down_price;

            if ($firstInstallment->paid_amount >= $firstInstallment->amount) {
                $firstInstallment->status = 'paid';
            } elseif ($firstInstallment->paid_amount > 0) {
                $firstInstallment->status = 'partial';
            }

            $firstInstallment->save();

            InstallmentPayment::create([
                'installment_id' => $firstInstallment->id,
                'amount' => $purchase->down_price,
                'paid_at' => now(),
            ]);
        }

        // PDF Invoice Generation
        $invoices = Invoice::all();
        $data = [
            'invoices'     => $invoices,
            'purchase'     => $purchase,
            'emiAmount'    => $emiAmount,
            'installments' => $installments,
            'customer'     => $purchase->customer,
            'product'      => $purchase->product,
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


    public function show(Purchase $purchase)
    {
        //
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
            'customer_id'  => 'required|exists:customers,id',
            'product_id'   => 'required|exists:products,id',
            'model_id'     => 'required',
            'sales_price'  => 'required|numeric',
            'down_price'   => 'required|numeric',
            'net_price'    => 'required|numeric',
            'emi_plan'     => 'required|integer|min:1',
        ]);

        $purchase->update([
            'customer_id' => $request->customer_id,
            'product_id'  => $request->product_id,
            'model_id'    => $request->model_id,
            'sales_price' => $request->sales_price,
            'down_price'  => $request->down_price,
            'net_price'   => $request->net_price,
            'emi_plan'    => $request->emi_plan,
        ]);

        return redirect()->route('purchases.index')->with('success', 'Purchase updated successfully.');
    }

    public function destroy(Purchase $purchase)
    {
        $purchase->delete();
        return redirect()->route('purchases.index')->with('success', 'Purchase deleted successfully.');
    }
}
