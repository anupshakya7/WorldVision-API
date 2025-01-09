<?php

namespace App\Http\Controllers\ATI\Admin;

use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Models\Admin\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CountryController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $countries = Country::with(['user','parentData'])->where('level',1)->where('ati',1)->paginate(10);
        $countries = PaginationHelper::addSerialNo($countries);

        return view('ati.admin.dashboard.country.index', compact('countries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $regions = Country::select('id', 'country')->where('level', 0)->get();

        return view('ati.admin.dashboard.country.create', compact('regions'));
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
            'country' => 'required|string|max:255',
            'country_code' => 'nullable|string|max:3',
            'geometry' => 'nullable|string',
            'parent_id' => 'nullable|exists:countries,id',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'bounding_box' => 'nullable|string',
        ]);

        //Parent Id is null
        if (!isset($validatedData['parent_id'])) {
            $validatedData['parent_id'] = null;
            $validatedData['level'] = 0;
        } else {
            $validatedData['level'] = 1;
        }

        //ATI Country
        $validatedData['ati'] = 1;

        //Adding Created By User Id
        $validatedData['created_by'] = Auth::user()->id;
        $validatedData['company_id'] = Auth::user()->company_id;

        //Create a new country
        $country = Country::create($validatedData);

        return redirect()->route('admin.ati.country.index')->with('success', 'Country created successfully!!!');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $country = Country::with(['user','parentData'])->filterATICountry()->find($id);
        
        if($country){
            return view('ati.admin.dashboard.country.view', compact('country'));
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
    public function edit(Country $country)
    {
        $regions = Country::select('id', 'country')->where('level', 0)->get();
        $country = Country::filterATICountry()->find($country->id);

        if($country){
            return view('ati.admin.dashboard.country.edit', compact('regions', 'country'));
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
    public function update(Request $request, Country $country)
    {
        $validatedData = $request->validate([
            'country' => 'required|string|max:255',
            'country_code' => 'nullable|string|max:3',
            'geometry' => 'nullable|string',
            'parent_id' => 'nullable|exists:countries,id',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'bounding_box' => 'nullable|string',
        ]);

        //Parent Id is null
        if (!isset($validatedData['parent_id'])) {
            $validatedData['parent_id'] = null;
            $validatedData['level'] = 0;
        } else {
            $validatedData['level'] = 1;
        }

        //ATI Country
        $validatedData['ati'] = 1;

        //Adding Created By User Id
        $validatedData['created_by'] = Auth::user()->id;
        $validatedData['company_id'] = Auth::user()->company_id;

        //Create a new country
        $country = $country->update($validatedData);

        return redirect()->route('admin.ati.country.index')->with('success', 'Country updated successfully!!!');
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
