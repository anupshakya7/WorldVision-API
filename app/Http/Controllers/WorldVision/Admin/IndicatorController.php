<?php

namespace App\Http\Controllers\WorldVision\Admin;

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
        $indicators = Indicator::with('user')->filterIndicator()->paginate(10);
        $indicators = PaginationHelper::addSerialNo($indicators);

        if($indicators){
            return view('worldvision.admin.dashboard.indicator.index', compact('indicators'));
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
        $domains = Indicator::select('id','variablename')->where('level',0)->filterIndicator()->get();

        return view('worldvision.admin.dashboard.indicator.create',compact('domains'));
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
            'vardescription' => 'required|string',
            'varunits' => 'nullable|string',
            'is_more_better' => 'nullable',
            'transformation' => 'nullable|string',
            'lower' => 'nullable|numeric|between:-999.9,999.9',
            'upper' => 'nullable|integer|min:0',
            'sourcelinks' => 'nullable|string',
            'subnational' => 'nullable|string',
            'national' => 'nullable|string',
            'imputation' => 'nullable|string',
        ]);

        if($request->filled('domain_id')){
            $domain = Indicator::select('variablename')->find($request->domain_id);
            
            //Adding Domain in Domain Fields
            $validatedData['domain'] = $domain->variablename;
            $validatedData['level'] = 1;
        }else{
            $validatedData['domain'] = 'Multidimensional Child Vulnerability Score';
            $validatedData['level'] = 0;
        }
        

        //Adding Created By User Id
        $validatedData['created_by'] = Auth::user()->id;
        $validatedData['company_id'] = Auth::user()->company_id;

        //Create a new country
        $indicator = Indicator::create($validatedData);

        return redirect()->route('admin.indicator.index')->with('success', 'Indicator created successfully!!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $indicator = Indicator::with('user')->filterIndicator()->find($id);

        if($indicator){
            return view('worldvision.admin.dashboard.indicator.view', compact('indicator'));
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
        $indicator = $indicator->filterIndicator()->find($indicator->id);
        
        if($indicator){
            $domains = Indicator::select('id','variablename')->where('level',0)->filterIndicator()->get();

            return view('worldvision.admin.dashboard.indicator.edit', compact('indicator','domains'));
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
            'vardescription' => 'required|string',
            'varunits' => 'nullable|string',
            'is_more_better' => 'nullable',
            'transformation' => 'nullable|string',
            'lower' => 'nullable|numeric|between:-999.9,999.9',
            'upper' => 'nullable|integer|min:0',
            'sourcelinks' => 'nullable|string',
            'subnational' => 'nullable|string',
            'national' => 'nullable|string',
            'imputation' => 'nullable|string',
        ]);

        if($request->filled('domain_id')){
            $domain = Indicator::select('variablename')->find($request->domain_id);
            
            //Adding Domain in Domain Fields
            $validatedData['domain'] = $domain->variablename;
            $validatedData['level'] = 1;
        }else{
            $validatedData['domain'] = 'Multidimensional Child Vulnerability Score';
            $validatedData['level'] = 0;
        }

        //Adding Created By User Id
        $validatedData['created_by'] = Auth::user()->id;
        $validatedData['company_id'] = Auth::user()->company_id;

        //Create a new country
        $indicator = $indicator->update($validatedData);

        return redirect()->route('admin.indicator.index')->with('success', 'Indicator updated successfully!!!');
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
