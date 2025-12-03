<?php

namespace App\Http\Controllers;

use App\Models\SubCategory;
use App\Models\Category;
use App\Models\UserLog;
use App\Traits\PdfReportTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    use PdfReportTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sub_categories = SubCategory::all();
        $categories = Category::all();

        $currentModule = 'subcategories module';
        $previousModule = session('current_module');

        session(['current_module' => $currentModule]);

        // Log new module access
        UserLog::create([
            'name'    => Auth::user()->name,
            'actions' => 'Accessed ' . $currentModule,
            'table'   => "sub_categories",
            'url'     => request()->fullUrl(),
            'user_id' => Auth::id(),
        ]);

        if ($previousModule && $previousModule !== $currentModule) {
            UserLog::create([
                'name'    => Auth::user()->name,
                'actions' => 'Exited ' . $previousModule,
                'url'     => request()->fullUrl(),
                'table'   => "sub_categories",
                'user_id' => Auth::id(),
            ]);
        }

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
            'actions'      => 'Added new subcategory (' . $request->sub_category_name . ')',
            'url'          => $request->fullUrl(),
            'reference_id' => $category->id,
            'table'        => "sub_categories",
            'user_id'      => Auth::id(),
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
            'actions'      => 'Updated ' . $request->sub_category_name . ' subcategory details',
            'url'          => $request->fullUrl(),
            'reference_id' => $sub_category->id,
            'table'        => "sub_categories",
            'user_id'      => Auth::id(),
        ]);

        return redirect()->route('sub_categories.index')->with('success', 'Sub-category updated successfully.');
    }

    public function sub_categories_report(Request $request)
    {
        $sub_categories = SubCategory::all();

        UserLog::create([
            'name'         => Auth::user()->name,
            'actions'      => 'Generated all sub categories report',
            'url'          => $request->fullUrl(),
            'table'        => "clients",
            'user_id'      => Auth::id(),
        ]);

        return $this->renderPdfWithPageNumbers(
            'clients.sub_categories_report',
            ['sub_categories' => $sub_categories],
            'sub_categories_report.pdf',
            'a4',
            'landscape'
        );
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
            'actions'      => 'Deleted ' . $request->sub_category_name . ' subcategory',
            'url'          => $request->fullUrl(),
            'reference_id' => $sub_category->id,
            'table'        => "sub_categories",
            'user_id'      => Auth::id(),
        ]);

        return redirect()->route('sub_categories.index')->with('success', 'Category deleted successfully.');
    }
}
