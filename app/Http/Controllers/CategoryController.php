<?php

namespace App\Http\Controllers;

use App\Models\UserLog;
use Illuminate\Support\Facades\Auth;
use App\Traits\PdfReportTrait;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use PdfReportTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = Category::all();

        UserLog::create([
            'name'         => Auth::user()->name,
            'actions'      => Auth::user()->name . ' viewed categories at ' . now(),
            'url'          => $request->fullUrl(),
            'table'        => "client_categories",
            'user_id'      => Auth::id(),
        ]);

        return view('categories.index')->with('categories',$categories);
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
        $validatedData = $request->validate([
            'category_name'=>'required',
            'description' => 'nullable|string',
        ]);

        $category = new Category($validatedData);
        $category->save();

        UserLog::create([
            'name'         => Auth::user()->name,
            'actions'      => Auth::user()->name . ' added ' . $request->category_name . ' to client categories list at ' . now(),
            'url'          => $request->fullUrl(),
            'reference_id' => $category->id,
            'table'        => "client_categories",
            'user_id'      => Auth::id(),
        ]);
        
        return redirect()->route('categories.index')->with('success', 'Category Saved Successfully');
    }

    public function categories_report(Request $request)
    {
        $categories = Category::all();

        UserLog::create([
            'name'         => Auth::user()->name,
            'actions'      => 'Generated all categories report',
            'url'          => $request->fullUrl(),
            'table'        => "clients",
            'user_id'      => Auth::id(),
        ]);

        return $this->renderPdfWithPageNumbers(
            'clients.categories_report',
            ['categories' => $categories],
            'categories_report.pdf',
            'a4',
            'landscape'
        );
    }


    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'category_name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        $category = Category::findOrFail($id);
        $category->update($validated);

        UserLog::create([
            'name'         => Auth::user()->name,
            'actions'      => 'Updated ' . $request->category_name . ' in client categories list',
            'url'          => $request->fullUrl(),
            'reference_id' => $category->id,
            'table'        => "client_categories",
            'user_id'      => Auth::id(),
        ]);

        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        UserLog::create([
            'name'         => Auth::user()->name,
            'actions'      => 'Deleted ' . $request->category_name . ' from the client categories list',
            'url'          => $request->fullUrl(),
            'reference_id' => $category->id,
            'table'        => "client_categories",
            'user_id'      => Auth::id(),
        ]);

        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }

}
