<?php

namespace App\Http\Controllers\ATI\Admin;

use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Models\Admin\Indicator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndicatorController extends Controller
{
        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $indicators = Indicator::select('id','domain','domain_id','variablename_long','variablename','vardescription','level','created_by')->with(['user','domains'])->filterIndicator()->paginate(10);
        //Serial No
        $indicators = PaginationHelper::addSerialNo($indicators);

        return view('ati.admin.dashboard.indicator.index', compact('indicators'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $domains = Indicator::where('level',0)->filterIndicator()->whereNotIn('variablename',['Overall Score','ATI Governance'])->get();
        return view('ati.admin.dashboard.indicator.create',compact('domains'));
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
            'domain_id' => 'nullable|integer',
            'variablename_long' => 'required|string',
            'variablename' => 'required|string',
            'vardescription' => 'nullable|string'
        ]);

        if($validatedData['domain_id'] !== null){
            $validatedData['level'] = 1;
        }else{
            $validatedData['level'] = 0;
        }
        //Adding Created By User Id
        $validatedData['created_by'] = Auth::user()->id;
        $validatedData['company_id'] = Auth::user()->company_id;
        
        //Create a new country
        $indicator = Indicator::create($validatedData);

        return redirect()->route('admin.ati.indicator.index')->with('success', 'Indicator created successfully!!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $indicator = Indicator::with(['user','domains'])->filterIndicator()->find($id);

        if($indicator){
            return view('ati.admin.dashboard.indicator.view', compact('indicator'));
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
    public function edit(Indicator $indicator)
    {
        $domains = Indicator::where('level',0)->filterIndicator()->whereNotIn('variablename',['Overall Score','ATI Governance'])->get();
        $indicator = $indicator->filterIndicator()->find($indicator->id);
        
        if($indicator){
            return view('ati.admin.dashboard.indicator.edit', compact('domains','indicator'));
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
    public function update(Request $request, Indicator $indicator)
    {
        $validatedData = $request->validate([
            'domain_id' => 'nullable|integer',
            'variablename_long' => 'required|string',
            'variablename' => 'required|string',
            'vardescription' => 'nullable|string'
        ]);

        if($validatedData['domain_id'] !== null){
            $validatedData['level'] = 1;
        }else{
            $validatedData['level'] = 0;
        }

        //Adding Created By User Id
        $validatedData['created_by'] = Auth::user()->id;
        $validatedData['company_id'] = Auth::user()->company_id;

        //Create a new country
        $indicator = $indicator->update($validatedData);

        return redirect()->route('admin.ati.indicator.index')->with('success', 'Indicator updated successfully!!!');
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
