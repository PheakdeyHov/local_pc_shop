<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    public function search(Request $request)
    {
        $searchTerm = $request->get('name'); // Get the search term from the request

        // Query to filter categories based on the 'name' field
        if ($searchTerm) {
            // Filter categories by name
            $customers = Customer::where('name', 'like', '%' . $searchTerm . '%')->get();
        } else {
            // If no search term, return all categories
            $customers = Customer::all();
        }

        return view('customers.index', compact('customers'));
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = Customer::all();

        return view('customers.index',compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // Define validation rules
    $rules = [
        'customer_type' => ['required', 'integer'],
        'name' => ['nullable', 'string'],
        'address' => ['nullable', 'string'],
        'phonenumber' => ['nullable', 'string'],
    ];

    if ($request->customer_type == 1) {
        $rules = array_merge($rules, [
            'name' => ['required', 'string'],
            'address' => ['required', 'string'],
            'phonenumber' => ['required', 'string'],
        ]);
    }

    // Validate the request
    $validatedData = $request->validate($rules);

    // Automatically set the name to "Walk in customer" if customer_type is 0
    if ($request->customer_type == 0) {
        $validatedData['name'] = 'Walk in customer';
    }

    // Create the customer record
    Customer::create($validatedData);

    // Flash success message and redirect
    toastr()->success('Customer Created Successfully');
    return redirect()->route('customers.index');
}




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
    public function edit(string $id)
    {
        $customers = Customer::findOrFail($id);

        return view('customers.edit' , compact('customers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
{
    // Define validation rules
    $rules = [
        'customer_type' => ['required', 'integer'],
        'name' => ['nullable', 'string'],
        'address' => ['nullable', 'string'],
        'phonenumber' => ['nullable', 'string', Rule::unique('customers', 'phonenumber')->ignore($id)],
    ];

    if ($request->customer_type == 1) {
        $rules = array_merge($rules, [
            'name' => ['required', 'string'],
            'address' => ['required', 'string'],
            'phonenumber' => ['required', 'string', Rule::unique('customers', 'phonenumber')->ignore($id)],
        ]);
    }

    // Validate the request
    $validatedData = $request->validate($rules);

    // Automatically set the name to "Walk in customer" if customer_type is 0
    if ($request->customer_type == 0) {
        $validatedData['name'] = 'Walk in customer';
    }

    // Find the customer by ID and update their details
    $customer = Customer::findOrFail($id);
    $customer->update($validatedData);

    // Flash success message and redirect
    toastr()->success('Customer Updated Successfully');
    return redirect()->route('customers.index');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $customers = Customer::findOrFail($id);

        $customers->delete();

        toastr()->success('Customer Deleted Successfully');
    return redirect()->route('customers.index');
    }
}
