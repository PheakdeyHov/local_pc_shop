<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function search(Request $request)
    {
        $searchTerm = $request->get('name'); // Get the search term from the request

        // Query to filter categories based on the 'name' field
        if ($searchTerm) {
            // Filter categories by name
            $products = Product::where('name', 'like', '%' . $searchTerm . '%')->get();
        } else {
            // If no search term, return all categories
            $products = Product::all();
        }

        return view('products.index', compact('products'));
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        return view('products.index' , compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        return view('products.create' , compact('categories','brands'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'specifications.*.product_code' => 'nullable|unique:specifications,product_code',
            'specifications.*.specs_value' => 'required|string',
            'specifications.*.status' => 'required|string|in:new,second-hand,99',
            'specifications.*.qty' => 'required|integer|min:0',
            'specifications.*.purchase_price' => 'required|numeric|min:0',
            'specifications.*.sale_price' => 'required|numeric|min:0',
        ]);

        // Handle image upload if provided
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('uploads', 'public');
        }

        // Create the product
        $product = Product::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'image' => $imagePath,
        ]);

        // Check and add specifications if provided
        if ($request->has('specifications') && is_array($request->specifications)) {
            foreach ($request->specifications as $spec) {

                // Create the specification for the product
                $product->specifications()->create([
                    'product_code' => $spec['product_code'] ?? null,
                    'specs_value' => $spec['specs_value'],
                    'status' => $spec['status'],
                    'qty' => $spec['qty'],
                    'purchase_price' => $spec['purchase_price'],
                    'sale_price' => $spec['sale_price'],
                ]);
            }
        }

        // Flash success message and redirect
        toastr()->success('Product Created Successfully');
        return redirect()->route('products.index');
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
        $products = Product::findOrFail($id);
        $categories = Category::all();
        $brands = Brand::all();

        return view('products.edit' , compact('products','categories','brands'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'specifications.*.product_code' => 'nullable',
            'specifications.*.specs_value' => 'required|string',
            'specifications.*.status' => 'required|string|in:new,second-hand,99',
            'specifications.*.qty' => 'required|integer|min:0',
            'specifications.*.purchase_price' => 'required|numeric|min:0',
            'specifications.*.sale_price' => 'required|numeric|min:0',
        ]);

        $product = Product::findOrFail($id);
        $product->update($request->only('name', 'brand_id', 'category_id'));

        // If a new logo file is uploaded, store it, otherwise, keep the existing one
        if ($request->hasFile('image')) {
            // Delete the old logo if it exists
            if ($product->image && file_exists(storage_path('app/public/' . $product->image))) {
                unlink(storage_path('app/public/' . $product->image));
            }

            // Store the new logo and update the logo path
            $imagePath = $request->file('image')->store('uploads', 'public');
            $product->image = $imagePath;
        }

        $product->specifications()->delete();

        foreach ($request->specifications as $spec) {
            $product->specifications()->create($spec);
        }

        // Flash success message and redirect
        toastr()->success('Product Updated Successfully');
        return redirect()->route('products.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $products = Product::findOrFail($id);

        $products->delete();

        // Flash success message and redirect
        toastr()->success('Product Deleted Successfully');
        return redirect()->route('products.index');
    }
}
