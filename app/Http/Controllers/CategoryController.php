<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Flasher\Prime\FlasherInterface;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function search(Request $request)
    {
        $searchTerm = $request->get('name'); // Get the search term from the request

        // Query to filter categories based on the 'name' field
        if ($searchTerm) {
            // Filter categories by name
            $categories = Category::where('name', 'like', '%' . $searchTerm . '%')->get();
        } else {
            // If no search term, return all categories
            $categories = Category::all();
        }

        return view('categories.index', compact('categories'));
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();

        return view('categories.index' , compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "name"=>"required|unique:categories,name"
        ]);

        Category::create([
            "name"=>$request->name,
        ]);

        toastr()->success('Category Created Successfully');
        return redirect()->route('categories.index');
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
        $categories = Category::findOrFail($id);

        return view('categories.edit' , compact('categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            "name"=>"required|unique:categories,name,$id,id"
        ]);

        $categories = Category::findOrFail($id);

        $categories->name = $request->name;
        $categories->save();

        toastr()->success('Category Updated Successfully');
        return redirect()->route('categories.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $categories = Category::findOrFail($id);

        $categories->delete();

        toastr()->success('Category Deleted Successfully');
        return redirect()->route('categories.index');
    }
}
