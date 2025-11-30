<?php

namespace App\Livewire;

use Livewire\Component;

class Customer extends Component
{
      
    
       /**
     * Display a listing of the resource.
     */

    public function __construct()
    {
        // $this->middleware(['permission:installment-pay|installment-create|installment-edit|installment-delete'], ['only' => ['index', 'show']]);
        $this->middleware(['permission:customer-list|customer-create|customer-edit|customer-delete'], ['only' => ['index', 'show']]);
        $this->middleware(['permission:customer-create'], ['only' => ['create', 'store']]);
        $this->middleware(['permission:customer-edit'], ['only' => ['edit', 'update']]);
        $this->middleware(['permission:customer-delete'], ['only' => ['destroy']]);
    }

    public function render(Request $request)
    {
        
        $search = $request->input('search');

       $query = Customer::with('location')->orderBy('customer_id', 'asc');


        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_id', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        // Important: persist search in pagination
        $customers = $query->paginate(100)->appends(['search' => $search]);

        // AJAX request response
        if ($request->ajax()) {
            $html = view('customers.partials.customer_table_rows', compact('customers'))->render();
            $pagination = $customers->links()->toHtml();

            return response()->json([
                'html' => $html,
                'count' => $customers->total(),
                'pagination' => $pagination
            ]);
        }

        // Non-AJAX normal full page load
        return view('customers.index', compact('customers', 'search'));
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
            'customer_id' => 'required|integer|unique:customers,customer_id',
            'customer_phone'   => 'required|string|unique:customers,customer_phone',
            'customer_phone2'   => 'required|string|unique:customers,customer_phone2',
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
        $customer = Customer::create([
            'customer_name'    => $validated['customer_name'],
            'customer_id'      => $validated['customer_id'],
            'customer_phone'   => $validated['customer_phone'],
            'customer_phone2'   => $validated['customer_phone2'],
            'customer_image'   => $customerImage,
            'landlord_name'    => $validated['landlord_name'],
            'location_id'      => $validated['location_id'],
            'location_details' => $validated['location_details'],
        ]);

        // toastr()->addSuccess('Customer created!');
        // // Mail::to('sclsumonislam@gmail.com')->send(new NewCustomerNotification($customer));

        // Redirect to the customer index page with a success message
        return redirect()->route('customers.index');
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
        $customer = Customer::findOrFail($id);

        $validated = $request->validate([
            'customer_name'    => 'required|string|max:255',
            'customer_id'      => 'required|integer',
            'customer_phone'   => 'required|string|unique:customers,customer_phone,' . $customer->id,
            'customer_phone2'   => 'required',
            'customer_image'   => 'nullable|image|mimes:jpg,jpeg,png',
            'landlord_name'    => 'nullable|string|max:255',
            'location_id'      => 'required|exists:locations,id',
            'location_details' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('customer_image')) {
            $image = $request->file('customer_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('image/customers'), $imageName);
            $customer->customer_image = 'image/customers/' . $imageName;
        }

        $customer->customer_name    = $validated['customer_name'];
        $customer->customer_id      = $validated['customer_id'];
        $customer->customer_phone   = $validated['customer_phone'];
        $customer->customer_phone2   = $validated['customer_phone2'];
        $customer->landlord_name    = $validated['landlord_name'];
        $customer->location_id      = $validated['location_id'];
        $customer->location_details = $validated['location_details'];
        $customer->save();

        $search = $request->input('search');
        

        return redirect()->route('customers.index', ['search' => $search])
            ->with('success', 'Customer updated successfully.');
    }




    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {

        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully.');
    }



    // app/Http/Controllers/CustomerController.php

    public function customerEmiPlans($id)
    {
        $customer = Customer::with('purchases.installments')->findOrFail($id);

        $paymentHistory = InstallmentPayment::with('installment.purchase.product')
            ->whereHas('installment.purchase', function ($query) use ($id) {
                $query->where('customer_id', $id);
            })
            ->orderBy('paid_at', 'desc')
            ->get();

        return view('customers.emi_plans', compact('customer', 'paymentHistory'));
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

        $customers = $customersQuery->latest()->paginate(500);

        return view('customers.show', compact('location', 'customers'));
    }
}
