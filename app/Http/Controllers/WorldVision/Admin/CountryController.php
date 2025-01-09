<?php

namespace App\Http\Controllers\WorldVision\Admin;

use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Country\CountryRequest;
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
        $countries = Country::with(['user','parentData'])->paginate(10);
        $countries = PaginationHelper::addSerialNo($countries);

        return view('worldvision.admin.dashboard.country.index', compact('countries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $regions = Country::select('id', 'country')->where('level', 0)->get();

        return view('worldvision.admin.dashboard.country.create', compact('regions'));
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

        //Adding Created By User Id
        $validatedData['created_by'] = Auth::user()->id;
        $validatedData['company_id'] = Auth::user()->company_id;

        //Create a new country
        $country = Country::create($validatedData);

        return redirect()->route('admin.country.index')->with('success', 'Country created successfully!!!');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $country = Country::with(['user','parentData'])->find($id);

        return view('worldvision.admin.dashboard.country.view', compact('country'));
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
        return view('worldvision.admin.dashboard.country.edit', compact('regions', 'country'));
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

        //Adding Created By User Id
        $validatedData['created_by'] = Auth::user()->id;
        $validatedData['company_id'] = Auth::user()->company_id;

        //Create a new country
        $country = $country->update($validatedData);

        return redirect()->route('admin.country.index')->with('success', 'Country updated successfully!!!');
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
