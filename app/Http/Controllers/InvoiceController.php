<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index()
    {

        return view('invoices.index');
    }

    /**
     * Show the form for creating a new invoice.
     */
    public function create()
    {
        return view('invoices.create');
    }

    /**
     * Store a newly created invoice in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'header_name' => 'required|string|max:255',
            'bill_to' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'location' => 'required|string|max:255',
            'product_name' => 'required|string|max:255',
            'product_model' => 'required|string|max:255',
            'total_price' => 'required|string|max:255',
            'down_payment' => 'required|string|max:255',
            'emi_month' => 'required|string|max:255',
            'next_emi_amount' => 'required|string|max:255',
            'footer_name' => 'required|string|max:255',
        ]);

        Invoice::create($validated);

        return redirect()->route('invoices.index')->with('success', 'Invoice created successfully.');
    }

    /**
     * Show the form for editing the specified invoice.
     */
    public function edit(Invoice $invoice)
    {
        return view('invoices.edit', compact('invoice'));
    }

    /**
     * Update the specified invoice in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'header_name' => 'required|string|max:255',
            'bill_to' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'location' => 'required|string|max:255',
            'product_name' => 'required|string|max:255',
            'product_model' => 'required|string|max:255',
            'total_price' => 'required|string|max:255',
            'down_payment' => 'required|string|max:255',
            'emi_month' => 'required|string|max:255',
            'next_emi_amount' => 'required|string|max:255',
            'footer_name' => 'required|string|max:255',
        ]);

        $invoice->update($validated);

        return redirect()->route('invoices.index')->with('success', 'Invoice updated successfully.');
    }

    /**
     * Remove the specified invoice from storage.
     */
    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully.');
    }
}
