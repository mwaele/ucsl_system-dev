<?php

namespace App\Http\Controllers;

use App\Models\SubCategory;
use App\Models\Category;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sub_categories = SubCategory::all();
        $categories = Category::all();

        return view('sub_categories.index')->with(['sub_categories'=>$sub_categories,'categories'=>$categories]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'sub_category_name'=>'required|string',
            'description' => 'nullable|string',
        ]);

        $category = new SubCategory($validatedData);
        $category->save();
        
        return redirect()->route('sub_categories.index')->with('success', 'Sub-category saved successfully');
    
    }

    /**
     * Display the specified resource.
     */
    public function show(SubCategory $subCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SubCategory $subCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'sub_category_name' => 'string',
            'description' => 'string',
        ]);

        $sub_category = SubCategory::findOrFail($id);
        $sub_category->update($validated);

        return redirect()->route('sub_categories.index')->with('success', 'Sub-category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $sub_category = SubCategory::findOrFail($id);
        $sub_category->delete();

        return redirect()->route('sub_categories.index')->with('success', 'Category deleted successfully.');
    }
}
