<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function search(Request $request)
    {
        $searchTerm = $request->get('name'); // Get the search term from the request

        // Query to filter categories based on the 'name' field
        if ($searchTerm) {
            // Filter categories by name
            $brands = Brand::where('name', 'like', '%' . $searchTerm . '%')->get();
        } else {
            // If no search term, return all categories
            $brands = Brand::all();
        }

        return view('brands.index', compact('brands'));
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brands = Brand::all();
        return view('brands.index', compact('brands'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('brands.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:brands,name',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'required|boolean: '
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('uploads', 'public');
        }

        Brand::create([
            'name' => $request->name,
            'logo' => $logoPath,
            'status' => $request->status,
        ]);

        toastr()->success('Brand Created Successfully');
        return redirect()->route('brands.index');
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
        $brands = Brand::findOrFail($id);
        return view('brands.edit', compact('brands'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => "required|unique:brands,name,$id,id", // Ensure name is unique except for the current brand
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Validate image file
            'status' => 'required|boolean', // Ensure status is a boolean
        ]);

        // Retrieve the brand record to be updated
        $brands = Brand::findOrFail($id);

        // If a new logo file is uploaded, store it, otherwise, keep the existing one
        if ($request->hasFile('logo')) {
            // Delete the old logo if it exists
            if ($brands->logo && file_exists(storage_path('app/public/' . $brands->logo))) {
                unlink(storage_path('app/public/' . $brands->logo));
            }

            // Store the new logo and update the logo path
            $logoPath = $request->file('logo')->store('uploads', 'public');
            $brands->logo = $logoPath;
        }

        // Update other brand fields
        $brands->name = $request->name;
        $brands->status = $request->status;

        // Save the updated brand
        $brands->save();

        // Provide feedback using toastr and redirect
        toastr()->success('Brand Updated Successfully');
        return redirect()->route('brands.index');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $brands = Brand::findOrFail($id);

        $brands->delete();

        // Flash success message and redirect
        toastr()->success('Brand Deleted Successfully');
        return redirect()->route('brands.index');
    }
}
