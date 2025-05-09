<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Installment;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function __construct()
    {
        $this->middleware(['permission:customer-list|customer-create|customer-edit|customer-delete'], ['only' => ['index', 'show']]);
        $this->middleware(['permission:customer-create'], ['only' => ['create', 'store']]);
        $this->middleware(['permission:customer-edit'], ['only' => ['edit', 'update']]);
        $this->middleware(['permission:customer-delete'], ['only' => ['destroy']]);
    }


    public function index(Request $request)
    {
        $query = Customer::with('location')
                     ->orderBy('created_at', 'desc'); // Add orderBy to sort by created_at in descending order

    // Check if search query is filled
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('customer_name', 'like', "%{$search}%")
              ->orWhere('customer_id', 'like', "%{$search}%")
              ->orWhere('customer_phone', 'like', "%{$search}%");
        });
    }

    // Paginate the results
    $customers = $query->paginate(100); // Show 100 results per page

    // If the request is an AJAX request, return the HTML only
    if ($request->ajax()) {
        $html = view('customers.partials.customer_table_rows', compact('customers'))->render();
        $pagination = $customers->links(); // Get the pagination links
        $count = $customers->total(); // Total number of matching customers

        return response()->json([
            'html' => $html,
            'count' => $count,
            'pagination' => $pagination
        ]);
    }

    // Return the view with the paginated customers
    return view('customers.index', compact('customers'));
    
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $locations = Location::all();
        return view('customers.create', compact('locations'));
    }

    /**
     * Store a newly created resource in storage.
     */


    public function store(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'customer_name'    => 'required|string|max:255',
            'customer_id'      => 'required|integer',
            'customer_phone'   => 'required|string|unique:customers,customer_phone',
            'customer_image'   => 'nullable|image|mimes:jpg,jpeg,png',
            'landlord_name'    => 'nullable|string|max:255',
            'location_id'      => 'required|exists:locations,id',
            'location_details' => 'nullable|string|max:255',
        ]);

        // Handle image upload (if image exists)
        $customerImage = 'images/default.png'; // Default image
        if ($request->hasFile('customer_image')) {
            $image = $request->file('customer_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('image/customers'), $imageName);
            $customerImage = 'image/customers/' . $imageName;
        }


        // Insert the customer data in a single query
        Customer::create([
            'customer_name'    => $validated['customer_name'],
            'customer_id'      => $validated['customer_id'],
            'customer_phone'   => $validated['customer_phone'],
            'customer_image'   => $customerImage,
            'landlord_name'    => $validated['landlord_name'],
            'location_id'      => $validated['location_id'],
            'location_details' => $validated['location_details'],
        ]);

        // Redirect to the customer index page with a success message
        return redirect()->route('customers.index')->with('success', 'Customer created successfully.');
    }

    /**
     * Display the specified resource.
     */

    public function show(Request $request, $id)
    {

        $location = Location::findOrFail($id);

        // Start building the query from the customers of this location
        $customersQuery = $location->customers();

        $customers = $customersQuery->latest()->paginate(10);

        return view('customers.show', compact('location', 'customers'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        $locations = Location::all();
        return view('customers.edit', compact('customer', 'locations'));
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, $id)
    {
        // Find the customer
        $customer = Customer::findOrFail($id);

        // Validate the request data
        $validated = $request->validate([
            'customer_name'    => 'required|string|max:255',
            'customer_id'      => 'required|integer',
            'customer_phone'   => 'required|string|unique:customers,customer_phone,' . $customer->id,
            'customer_image'   => 'nullable|image|mimes:jpg,jpeg,png',
            'landlord_name'    => 'nullable|string|max:255',
            'location_id'      => 'required|exists:locations,id',
            'location_details' => 'nullable|string|max:255',
        ]);

        // Handle image upload (if a new image is uploaded)
        if ($request->hasFile('customer_image')) {
            $image = $request->file('customer_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('image/customers'), $imageName);
            $customer->customer_image = 'image/customers/' . $imageName;
        }

        // Update other fields
        $customer->customer_name    = $validated['customer_name'];
        $customer->customer_id      = $validated['customer_id'];
        $customer->customer_phone   = $validated['customer_phone'];
        $customer->landlord_name    = $validated['landlord_name'];
        $customer->location_id      = $validated['location_id'];
        $customer->location_details = $validated['location_details'];

        // Save changes
        $customer->save();

        // Redirect with success message
        return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
    }




    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {

        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully.');
    }




    public function customerEmiPlans($id)
    {


        $customer = Customer::with('purchases.installments')->findOrFail($id);
        $paymentHistory = session('paymentHistory', []); // Get session data


        return view('customers.emi_plans', compact('customer', 'paymentHistory'));
        // return view('customers.emi_plans', compact('customer'));
    }


    public function showByLocation(Request $request, $id)
    {
        $location = Location::findOrFail($id);

        $customersQuery = $location->customers(); // HasMany relationship

        // Filter by date
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $customersQuery->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        $customers = $customersQuery->latest()->paginate(10);

        return view('customers.show', compact('location', 'customers'));
    }
}
