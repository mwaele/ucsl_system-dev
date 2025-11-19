<?php

namespace App\Http\Controllers;

use App\Models\SubCategory;
use App\Models\Category;
use App\Models\UserLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sub_categories = SubCategory::all();
        $categories = Category::all();

        UserLog::create([
            'name'         => Auth::user()->name,
            'actions'      => Auth::user()->name . ' viewed subcategories at ' . now(),
            'url'          => $request->fullUrl(),
            'reference_id' => Auth::id(),
            'table'        => Auth::user()->getTable(),
        ]);

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

        UserLog::create([
            'name'         => Auth::user()->name,
            'actions'      => Auth::user()->name . ' added new subcategory (' . $request->sub_category_name . ') at ' . now(),
            'url'          => $request->fullUrl(),
            'reference_id' => Auth::id(),
            'table'        => Auth::user()->getTable(),
        ]);
        
        return redirect()->route('sub_categories.index')->with('success', 'Sub-category saved successfully');
    
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

        UserLog::create([
            'name'         => Auth::user()->name,
            'actions'      => Auth::user()->name . ' updated ' . $request->sub_category_name . ' subcategory details at ' . now(),
            'url'          => $request->fullUrl(),
            'reference_id' => Auth::id(),
            'table'        => Auth::user()->getTable(),
        ]);

        return redirect()->route('sub_categories.index')->with('success', 'Sub-category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $sub_category = SubCategory::findOrFail($id);
        $sub_category->delete();

        UserLog::create([
            'name'         => Auth::user()->name,
            'actions'      => Auth::user()->name . ' deleted ' . $request->sub_category_name . ' subcategory at ' . now(),
            'url'          => $request->fullUrl(),
            'reference_id' => Auth::id(),
            'table'        => Auth::user()->getTable(),
        ]);

        return redirect()->route('sub_categories.index')->with('success', 'Category deleted successfully.');
    }
}
