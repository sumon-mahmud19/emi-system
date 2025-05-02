<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductModel;
use Illuminate\Http\Request;

class ProductModelController extends Controller
{

    public function __construct()
    {
        $this->middleware(['permission:product-model-list|product-model-create|product-model-edit|product-model-delete'], ['only' => ['index', 'show']]);
        $this->middleware(['permission:product-model-create'], ['only' => ['create', 'store']]);
        $this->middleware(['permission:product-model-edit'], ['only' => ['edit', 'update']]);
        $this->middleware(['permission:product-model-delete'], ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $models = ProductModel::with('product')->latest()->get();

        // return $models;
        return view('models.index', compact('models'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::all();
        return view('models.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'model_name' => 'required|string|max:255',
        ]);

        ProductModel::create($request->all());

        return redirect()->back()->with('success', 'Product model created successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $model = ProductModel::findOrFail($id);
        $products = Product::all();

        return view('models.edit', compact('model', 'products'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'model_name' => 'required|string|max:255',
        ]);

        $model = ProductModel::findOrFail($id);
        $model->update([
            'product_id' => $request->product_id,
            'model_name' => $request->model_name,
        ]);

        return redirect()->route('models.index')->with('success', 'মডেল সফলভাবে আপডেট করা হয়েছে।');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $model = ProductModel::findOrFail($id);
        $model->delete();

        return redirect()->route('models.index')->with('success', 'মডেল সফলভাবে ডিলিট করা হয়েছে।');
    }
}
