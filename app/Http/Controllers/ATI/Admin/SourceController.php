<?php

namespace App\Http\Controllers\ATI\Admin;

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
        return view('ati.admin.dashboard.source.index', compact('sources'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $indicators = Indicator::select('id', 'variablename')->filterIndicator()->where('level',1)->get();

        return view('ati.admin.dashboard.source.create', compact('indicators'));
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
            'indicator_id' => 'required|integer|exists:indicators,id',
            'source' => 'required|string|max:255',
            'link' => 'nullable|url',
        ]);

        //Adding Created By User Id
        $validatedData['created_by'] = Auth::user()->id;
        $validatedData['company_id'] = Auth::user()->company_id;

        //Create a new country
        $source = Source::create($validatedData);

        return redirect()->route('admin.ati.ati-source.index')->with('success', 'Source created successfully!!!');
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
            return view('ati.admin.dashboard.source.view', compact('source'));
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
    public function edit($ati_source)
    {
        $source = Source::with(['indicator','user'])->where('id',$ati_source)->filterSource()->first();
        $indicators = Indicator::select('id', 'variablename')->filterIndicator()->get();

        if($source){
            return view('ati.admin.dashboard.source.edit', compact('indicators', 'source'));
        }else{
            return redirect()->back()->with('error','Data Not Found');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $ati_source)
    {
        $validatedData = $request->validate([
            'indicator_id' => 'required|integer|exists:indicators,id',
            'source' => 'required|string|max:255',
            'link' => 'nullable|url',
        ]);

        //Adding Created By User Id
        $validatedData['created_by'] = Auth::user()->id;
        $validatedData['company_id'] = Auth::user()->company_id;

        //Source
        $source = Source::with(['indicator','user'])->where('id',$ati_source)->filterSource()->first();

        //Update a new Source
        $source = $source->update($validatedData);

        return redirect()->route('admin.ati.ati-source.index')->with('success', 'Source updated successfully!!!');
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
