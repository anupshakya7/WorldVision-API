<?php

namespace App\Http\Controllers\ATI\Admin\CountryData;

use App\Helpers\PaginationHelper;
use App\Models\Admin\CountryDomainData;
use App\Http\Controllers\Controller;
use App\Models\Admin\Country;
use App\Models\Admin\CountryData;
use App\Models\Admin\Indicator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DomainScoreController extends Controller
{
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $countriesData = CountryData::with(['indicator','country','user'])->filterDomainScore()->paginate(10);
        $countriesData = PaginationHelper::addSerialNo($countriesData);
        $type = 'Domain';

        return view('ati.admin.dashboard.country_data.indicator_score.index', compact('countriesData','type'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $indicators = Indicator::select('id', 'variablename')->where('level',0)->filterIndicator()->get();
        $countries = Country::select('country','country_code')->where('level',1)->filterATICountry()->orderBy('country','ASC')->get();
        $type = 'Domain';
        return view('ati.admin.dashboard.country_data.indicator_score.create', compact('indicators','countries','type'));
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
            'indicator_id' => 'required|exists:indicators,id',
            'countrycode' => 'required|string|max:5|exists:countries,country_code',
            'year' => 'required|integer|between:2000,2100',
            'country_score' => 'required|numeric|between:0,999999.999999999',
            'banded' => 'required|numeric|between:0,999999.999999999',
            'imputed' => 'nullable|string',
            'remarks'=>'nullable|string'
        ]);

        //Adding Created By User Id
        $validatedData['created_by'] = Auth::user()->id;
        $validatedData['company_id'] = Auth::user()->company_id;
        $validatedData['political_context'] = 4;

        //Create a new domain data
        $countryData = CountryData::create($validatedData);

        return redirect()->route('admin.ati.domain-score.index')->with('success', 'Domain Score created successfully!!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
        $countryData = CountryData::with(['indicator','country','user'])->filterDomainScore()->find($id);

        if($countryData){
            $type="Domain";
            return view('ati.admin.dashboard.country_data.indicator_score.view', compact('countryData','type'));
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
    public function edit($id)
    {
        $countryData = CountryData::filterDomainScore()->find($id);
        $indicators = Indicator::select('id', 'variablename')->where('level',0)->filterIndicator()->get();
        $countries = Country::select('country','country_code')->where('level',1)->filterATICountry()->orderBy('country','ASC')->get();
    
        if($countryData){
            $type='Domain';
            return view('ati.admin.dashboard.country_data.indicator_score.edit', compact('indicators', 'countries','countryData','type'));
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
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'indicator_id' => 'required|exists:indicators,id',
            'countrycode' => 'required|string|max:5|exists:countries,country_code',
            'year' => 'required|integer|between:2000,2100',
            'country_score' => 'required|numeric|between:0,999999.999999999',
            'banded' => 'required|numeric|between:0,999999.999999999',
            'imputed' => 'nullable|string',
            'remarks'=>'nullable|string'
        ]);


        //Adding Created By User Id
        $validatedData['created_by'] = Auth::user()->id;
        $validatedData['company_id'] = Auth::user()->company_id;
        $validatedData['political_context'] = 4;

        //Create a new country
        $countryData = CountryData::find($id)->update($validatedData);

        return redirect()->route('admin.ati.domain-score.index')->with('success', 'Domain Score updated successfully!!!');
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
