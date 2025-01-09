<?php

namespace App\Http\Controllers\ATI\Admin\CountryData;

use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Models\Admin\Country;
use App\Models\Admin\CountryData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class VoiceOfPeopleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $countriesData = CountryData::with(['country','user'])->filterVoiceOfPeople()->paginate(10);
        $countriesData = PaginationHelper::addSerialNo($countriesData);

        return view('ati.admin.dashboard.country_data.voice_of_people.index', compact('countriesData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = Country::select('country','country_code')->where('level',1)->filterATICountry()->orderBy('country','ASC')->get();

        return view('ati.admin.dashboard.country_data.voice_of_people.create', compact('countries'));
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
            'year' => 'required|integer|between:2000,2100',
            'country_score' => 'required|numeric|between:0,999999.999999999',
            'remarks' => 'required|string|max:255',
        ]);

        //Adding Created By User Id
        $validatedData['created_by'] = Auth::user()->id;
        $validatedData['company_id'] = Auth::user()->company_id;
        $validatedData['political_context'] = 3;

        //Create a new country
        $countryData = CountryData::create($validatedData);

        return redirect()->route('admin.ati.voice-people.index')->with('success', 'Voice Of People created successfully!!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $countryData = CountryData::with(['country','user'])->filterVoiceOfPeople()->find($id);

        if($countryData){
            return view('ati.admin.dashboard.country_data.voice_of_people.view', compact('countryData'));
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
        $countryData = CountryData::filterVoiceOfPeople()->find($id);
        $countries = Country::select('country','country_code')->where('level',1)->filterATICountry()->orderBy('country','ASC')->get();

        if($countryData){
            return view('ati.admin.dashboard.country_data.voice_of_people.edit', compact('countries','countryData'));
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
            'year' => 'required|integer|between:2000,2100',
            'country_score' => 'required|numeric|between:0,999999.999999999',
            'remarks' => 'required|string|max:255',
        ]);

        //Adding Created By User Id
        $validatedData['created_by'] = Auth::user()->id;
        $validatedData['company_id'] = Auth::user()->company_id;
        $validatedData['political_context'] = 3;

        //Update a new voice of people
        $countryData = CountryData::find($id)->update($validatedData);

        return redirect()->route('admin.ati.voice-people.index')->with('success', 'Voice Of People updated successfully!!!');
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

    public function checkCountryYearWise(Request $request){
        $validatedData = Validator::make($request->all(),[
            'countrycode'=>'required|max:3',
            'year'=>'nullable',
        ]);

        if($validatedData->fails()){
            return response()->json($validatedData->errors(),404);
        }

        $voiceOfPeoples = ["The Judicial System","Politics","Elections"];

        $voiceOfPeopleData = [];

        $voiceOfPeopleQuery = CountryData::query();
        $voiceOfPeopleQuery->where('political_context',3);
        if($request->filled('countrycode') && $request->filled('year')){
            foreach($voiceOfPeoples as $voiceOfPeople){
                $voiceOfPeopleClone = clone $voiceOfPeopleQuery;
                $voiceOfPeopleData[$voiceOfPeople]  = $voiceOfPeopleClone->where('countrycode',$request->countrycode)->where('year',$request->year)->where('remarks',$voiceOfPeople)->count();
            }
        }

        if(count($voiceOfPeopleData)>0){
            return response()->json([
                'success'=>true,
                'data'=>$voiceOfPeopleData
            ]);
        }else{
            return response()->json([
                'success'=>false,
                'message'=>'No Data Found'
            ]);
        }   
    }
}
