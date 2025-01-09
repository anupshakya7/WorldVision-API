<?php

namespace App\Http\Controllers\WorldVision\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\CategoryColor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryColorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $colors = CategoryColor::with('user')->paginate(10);

        return view('worldvision.admin.dashboard.category_color.index', compact('colors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('worldvision.admin.dashboard.category_color.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'category' => 'required|string',
            'country_col_order' => 'required|integer',
            'country_leg_col' => 'required|string',
            'subcountry_col_order' => 'required|integer', 
            'subcountry_leg_col' => 'required|string',
        ]);

        //Adding Created By User Id
        $validatedData['created_by'] = Auth::user()->id;
        $validatedData['company_id'] = Auth::user()->company_id;

        //Create a new Category Color
        $colors = CategoryColor::create($validatedData);

        return redirect()->route('admin.category-color.index')->with('success', 'Category Color created successfully!!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $color = CategoryColor::with('user')->find($id);

        return view('worldvision.admin.dashboard.category_color.view', compact('color'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(CategoryColor $categoryColor)
    {
        return view('worldvision.admin.dashboard.category_color.edit', compact('categoryColor'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CategoryColor $categoryColor)
    {
        $validatedData = $request->validate([
            'category' => 'required|string',
            'country_col_order' => 'required|integer',
            'country_leg_col' => 'required|string',
            'subcountry_col_order' => 'required|integer', 
            'subcountry_leg_col' => 'required|string',
        ]);

        //Adding Created By User Id
        $validatedData['created_by'] = Auth::user()->id;
        $validatedData['company_id'] = Auth::user()->company_id;

        //Create a new country
        $categoryColor = $categoryColor->update($validatedData);

        return redirect()->route('admin.category-color.index')->with('success', 'Category Color updated successfully!!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
