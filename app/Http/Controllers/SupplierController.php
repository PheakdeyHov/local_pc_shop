<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function search(Request $request)
    {
        $searchTerm = $request->get('name'); // Get the search term from the request

        // Query to filter categories based on the 'name' field
        if ($searchTerm) {
            // Filter categories by name
            $suppliers = Supplier::where('name', 'like', '%' . $searchTerm . '%')->get();
        } else {
            // If no search term, return all categories
            $suppliers = Supplier::all();
        }

        return view('suppliers.index', compact('suppliers'));
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $suppliers = Supplier::all();
        return view('suppliers.index' , compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('suppliers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:suppliers,name',
            'address' => 'required',
            'phonenumber' => 'required|string|min:9|max:10',
            'email' => 'required'
        ]);

        Supplier::create([
            'name'=>$request->name,
            'address'=>$request->address,
            'phonenumber'=>$request->phonenumber,
            'email' =>$request->email,
        ]);

        toastr()->success('Supplier Created Succesfully');
        return redirect()->route('suppliers.index');
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
        $suppliers = Supplier::findOrFail($id);
        return view('suppliers.edit',compact('suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => "required|unique:suppliers,name,$id,id",
            'address' => 'required',
            'phonenumber' => 'required|string|min:9|max:10',
            'email' => 'required'
        ]);

        $suppliers = Supplier::findOrFail($id);
        $suppliers-> name = $request->name;
        $suppliers->address = $request->address;
        $suppliers->phonenumber = $request->phonenumber;
        $suppliers->email = $request->email;
        $suppliers->save();

        toastr()->success('Supplier Updated Succesfully');
        return redirect()->route('suppliers.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $suppliers = Supplier::findOrFail($id);

        $suppliers->delete();

        toastr()->success('Supplier Deleted Succesfully');
        return redirect()->route('suppliers.index');
    }
}
