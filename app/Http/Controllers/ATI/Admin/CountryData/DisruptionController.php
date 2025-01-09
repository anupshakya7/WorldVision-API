<?php

namespace App\Http\Controllers\ATI\Admin\CountryData;

use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Models\Admin\Country;
use App\Models\Admin\CountryData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DisruptionController extends Controller
{
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $countriesData = CountryData::with(['country','user'])->filterHistoricalDisruptionData()->paginate(10);
        $countriesData = PaginationHelper::addSerialNo($countriesData);

        return view('ati.admin.dashboard.country_data.historical_disruptions.index', compact('countriesData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = Country::withCount(['countryData'=>function($query){
            $query->where('political_context',1);
        }])->where('level',1)->filterATICountry()->having('country_data_count',0)->orderBy('country','ASC')->get();

        return view('ati.admin.dashboard.country_data.historical_disruptions.create', compact('countries'));
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
            'countrycode' => 'required|string|max:5|exists:countries,country_code',
            'country_score' => 'required|numeric|between:0,999999.999999999',
        ]);

        //Adding Created By User Id
        $validatedData['created_by'] = Auth::user()->id;
        $validatedData['company_id'] = Auth::user()->company_id;
        $validatedData['political_context'] = 1;

        //Create a new country
        $countryData = CountryData::create($validatedData);

        return redirect()->route('admin.ati.disruptions.index')->with('success', 'Historical Disruption created successfully!!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $countryData = CountryData::with(['country','user'])->filterHistoricalDisruptionData()->find($id);

        if($countryData){
            return view('ati.admin.dashboard.country_data.historical_disruptions.view', compact('countryData'));
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
        $countryData = CountryData::filterHistoricalDisruptionData()->find($id);
        $countries = Country::withCount(['countryData'=>function($query) use($id){
            $query->where('political_context',1)->where('id','!=',$id);
        }])->where('level',1)->filterATICountry()->having('country_data_count',0)->orderBy('country','ASC')->get();

        if($countryData){
            return view('ati.admin.dashboard.country_data.historical_disruptions.edit', compact('countries','countryData'));
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
            'countrycode' => 'required|string|max:5|exists:countries,country_code',
            'country_score' => 'required|numeric|between:0,999999.999999999'
        ]);

        //Adding Created By User Id
        $validatedData['created_by'] = Auth::user()->id;
        $validatedData['company_id'] = Auth::user()->company_id;
        $validatedData['political_context'] = 1;

        //Create a new country
        $countryData = CountryData::find($id)->update($validatedData);

        return redirect()->route('admin.ati.disruptions.index')->with('success', 'Historical Disruption updated successfully!!!');
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
