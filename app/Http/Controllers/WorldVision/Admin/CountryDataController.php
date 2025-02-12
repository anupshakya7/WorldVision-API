<?php

namespace App\Http\Controllers\WorldVision\Admin;

use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Jobs\CountryCSVData;
use App\Models\Admin\CategoryColor;
use App\Models\Admin\Country;
use App\Models\Admin\CountryData;
use App\Models\Admin\Indicator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;

class CountryDataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $countriesData = CountryData::with(['indicator','country','user'])->filterWorldVisionData()->paginate(10);
        $countriesData = PaginationHelper::addSerialNo($countriesData);

        return view('worldvision.admin.dashboard.country_data.index', compact('countriesData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $indicators = Indicator::select('id', 'variablename')->filterIndicator()->get();
        $countries = Country::select('country','country_code')->where('level',1)->get();
        $countries_colour = CategoryColor::select('country_leg_col','subcountry_leg_col','category')->get();

        return view('worldvision.admin.dashboard.country_data.create', compact('indicators','countries','countries_colour'));
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
            'country_col' => 'required|string',
            'country_cat' => 'required|string',
        ]);

        //Adding Created By User Id
        $validatedData['created_by'] = Auth::user()->id;
        $validatedData['company_id'] = Auth::user()->company_id;

        //Create a new country
        $countryData = CountryData::create($validatedData);

        return redirect()->route('admin.country-data.index')->with('success', 'Country Data created successfully!!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $countryData = CountryData::with(['indicator','country','user'])->filterWorldVisionData()->find($id);

        if($countryData){
            return view('worldvision.admin.dashboard.country_data.view', compact('countryData'));
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
        $countryData = CountryData::filterWorldVisionData()->find($id);
        $indicators = Indicator::select('id', 'variablename')->filterIndicator()->get();
        $countries = Country::select('country','country_code')->where('level',1)->get();
        $countries_colour = CategoryColor::select('country_leg_col','subcountry_leg_col','category')->get();

        if($countryData){
            return view('worldvision.admin.dashboard.country_data.edit', compact('indicators', 'countries','countries_colour','countryData'));
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
            'country_col' => 'required|string',
            'country_cat' => 'required|string',
        ]);

        //Adding Created By User Id
        $validatedData['created_by'] = Auth::user()->id;
        $validatedData['company_id'] = Auth::user()->company_id;

        //Create a new country
        $countryData = CountryData::find($id)->update($validatedData);

        return redirect()->route('admin.country-data.index')->with('success', 'Country Data updated successfully!!!');
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

    //Export csv file
    public function generateCSV(){
        $countriesData = CountryData::with(['indicator','country','user'])->filterWorldVisionData()->get();
        $filename = "country-data.csv";
        $fp = fopen($filename,'w+');
        fputcsv($fp,array('ID','Indicator','Country','Country Code','Year','Country Score','Country Color','Country Cateory','Remarks','Created By','Created At'));
 
        foreach($countriesData as $row){
            fputcsv($fp,array(
                $row->id,
                $row->indicator->variablename,
                optional($row->country)->country ?? 'No Country',
                $row->countrycode,
                $row->year,
                $row->country_score,
                $row->country_col,
                $row->country_cat,
                $row->remarks,
                $row->user->name,
                $row->created_at
            ));
        }

        fclose($fp);
        $headers = array('Content-Type'=>'text/csv');

        return response()->download($filename,'country-data.csv',$headers);
    }

    //Bulk Import
    public function bulk(){
        return view('worldvision.admin.dashboard.country_data.bulk');
    }

    public function bulkInsert(Request $request){
        $validatedData = $request->validate([
            'csv_file'=>'required|file|mimes:csv|max:500000'
        ]);

        if($request->has('csv_file')){
            $csv = file($request->csv_file);
            $csvarray = array_map('str_getcsv',$csv);

            //Header Start
            $header = [];
            $header = $csvarray[0];
            unset($csvarray[0]);
            //Header End

            $batch = Bus::batch([])->dispatch();
            $groupedData = [];

            //Grouping Data through Country
            foreach($csvarray as $row){
                $countryCode = $row[1];

                if(!isset($groupedData[$countryCode])){
                    $groupedData[$countryCode] = [];
                }
                $groupedData[$countryCode][] = $row;
            }
            $userId = auth()->user()->id;
            $companyId = auth()->user()->company_id;

            foreach($groupedData as $countryCode => $singleCountryData){
                $batch->add(new CountryCSVData($header,$singleCountryData,$countryCode,$userId,$companyId));
            }
        }

        $countriesData = CountryData::with(['indicator','country','user'])->paginate(10);

        return redirect()->route('admin.country-data.index',compact('countriesData'))->with('success','CSV import added on queue. Will update you once done!!!');
    }

}
