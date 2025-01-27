<?php

namespace App\Http\Controllers\WorldVision\Admin;

use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Models\Admin\Indicator;
use App\Models\Admin\Source;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SourceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sources = Source::with(['indicator','user'])->filterSource()->paginate(10);
        $sources = PaginationHelper::addSerialNo($sources);

        if($sources){
            return view('worldvision.admin.dashboard.source.index', compact('sources'));
        }else{
            return redirect()->back()->with('error','Data Not Found');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $indicators = Indicator::select('id', 'variablename')->filterIndicator()->get();

        return view('worldvision.admin.dashboard.source.create', compact('indicators'));
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
            'indicator_id' => 'nullable|integer|exists:indicators,id',
            'source' => 'required|string|max:255',
            'data_level' => 'nullable|integer|min:0',
            'impid' => 'required|string|min:0',
            'units' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'url' => 'nullable|string',
            'link' => 'nullable|string',
        ]);

        //Adding Created By User Id
        $validatedData['created_by'] = Auth::user()->id;
        $validatedData['company_id'] = Auth::user()->company_id;

        //Create a new country
        $source = Source::create($validatedData);

        return redirect()->route('admin.source.index')->with('success', 'Source created successfully!!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $source = Source::with(['indicator','user'])->filterSource()->find($id);

        if($source){
            return view('worldvision.admin.dashboard.source.view', compact('source'));
        }else{
            return redirect()->back()->with('error','Data Not Found');
        }       
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Source $source)
    {
        $indicators = Indicator::select('id', 'variablename')->filterIndicator()->get();
        return view('worldvision.admin.dashboard.source.edit', compact('indicators', 'source'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Source $source)
    {
        $validatedData = $request->validate([
            'indicator_id' => 'nullable|integer|exists:indicators,id',
            'source' => 'required|string|max:255',
            'data_level' => 'nullable|integer|min:0',
            'impid' => 'required|string|min:0',
            'units' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'url' => 'nullable|string',
            'link' => 'nullable|string',
        ]);

        //Adding Created By User Id
        $validatedData['created_by'] = Auth::user()->id;
        $validatedData['company_id'] = Auth::user()->company_id;

        //Update a new Source
        $source = $source->update($validatedData);

        return redirect()->route('admin.source.index')->with('success', 'Source updated successfully!!!');
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
