<?php

namespace App\Http\Controllers\WorldVision\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Country;
use App\Models\Admin\SubCountry;
use App\Models\Admin\SubCountryData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubCountryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $countries = Country::where('level',1)->get();
        $subCountries = SubCountry::paginate(10);

        if($subCountries){
            return view('worldvision.admin.dashboard.subcountry.index', compact('subCountries'));
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
        $countries = Country::select('country','country_code')->where('level', 1)->get();

        return view('worldvision.admin.dashboard.subcountry.create', compact('countries'));
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
            'countrycode' => 'required|string|size:3',
            'geocode'     => 'required|string',
            'geoname'     => 'required|string',
            'geometry'    => 'nullable|string',
        ]);

        //Adding Created By User Id
        $validatedData['created_by'] = Auth::user()->id;
        $validatedData['company_id'] = Auth::user()->company_id;

        //Create a new subcountry
        $subcountry = SubCountry::create($validatedData);

        return redirect()->route('admin.sub-country.index')->with('success', 'Sub Country created successfully!!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $subcountry = SubCountry::with('user')->find($id);

        if($subcountry){
            return view('worldvision.admin.dashboard.subcountry.view', compact('subcountry'));
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
        $countries = Country::select('country','country_code')->where('level', 1)->get();
        $subcountry = SubCountry::find($id);
        if($subcountry){
            return view('worldvision.admin.dashboard.subcountry.edit', compact('countries', 'subcountry'));
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
    public function update(Request $request, SubCountry $subCountry)
    {
        $validatedData = $request->validate([
            'countrycode' => 'required|string|size:3',
            'geocode'     => 'required|string',
            'geoname'     => 'required|string',
            'geometry'    => 'nullable|string',
        ]);

        //Adding Created By User Id
        $validatedData['created_by'] = Auth::user()->id;
        $validatedData['company_id'] = Auth::user()->company_id;

        //Create a new country
        $country = $subCountry->update($validatedData);

        return redirect()->route('admin.sub-country.index')->with('success', 'Sub Country updated successfully!!!');
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
