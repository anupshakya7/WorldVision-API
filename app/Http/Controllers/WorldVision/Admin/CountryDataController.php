<?php

namespace App\Http\Controllers\WorldVision\Admin;

use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Jobs\CountryCSVData;
use App\Mail\CountryDataErrorNotication;
use App\Models\Admin\CategoryColor;
use App\Models\Admin\Country;
use App\Models\Admin\CountryData;
use App\Models\Admin\Indicator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CountryDataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $countriesData = CountryData::with(['indicator', 'country', 'user'])->filterWorldVisionData()->paginate(10);
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
        $countries = Country::select('country', 'country_code')->where('level', 1)->get();
        $countries_colour = CategoryColor::select('country_leg_col', 'subcountry_leg_col', 'category')->get();

        return view('worldvision.admin.dashboard.country_data.create', compact('indicators', 'countries', 'countries_colour'));
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
        $countryData = CountryData::with(['indicator', 'country', 'user'])->filterWorldVisionData()->find($id);

        if ($countryData) {
            return view('worldvision.admin.dashboard.country_data.view', compact('countryData'));
        } else {
            return redirect()->back()->with('error', 'Data Not Found');
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
        $countries = Country::select('country', 'country_code')->where('level', 1)->get();
        $countries_colour = CategoryColor::select('country_leg_col', 'subcountry_leg_col', 'category')->get();

        if ($countryData) {
            return view('worldvision.admin.dashboard.country_data.edit', compact('indicators', 'countries', 'countries_colour', 'countryData'));
        } else {
            return redirect()->back()->with('error', 'Data Not Found');
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
    public function generateCSV()
    {
        $countriesData = CountryData::with(['indicator', 'country', 'user'])->filterWorldVisionData()->get();
        $filename = "country-data.csv";
        $fp = fopen($filename, 'w+');
        fputcsv($fp, array('ID', 'Indicator', 'Country', 'Country Code', 'Year', 'Country Score', 'Country Color', 'Country Cateory', 'Remarks', 'Created By', 'Created At'));

        foreach ($countriesData as $row) {
            fputcsv($fp, array(
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
        $headers = array('Content-Type' => 'text/csv');

        return response()->download($filename, 'country-data.csv', $headers);
    }

    //Bulk Import
    public function bulk()
    {
        return view('worldvision.admin.dashboard.country_data.bulk');
    }

    // public function bulkInsert(Request $request){
    //     $validatedData = $request->validate([
    //         'csv_file'=>'required|file|mimes:csv|max:500000'
    //     ]);

    //     if($request->has('csv_file')){
    //         $csv = file($request->csv_file);
    //         $chunks = array_chunk($csv,500);
    //         $header = [];
    //         $batch = Bus::batch([])->dispatch();

    //         foreach($chunks as $key=>$chunk){
    //             $data = array_map('str_getcsv',$chunk);

    //             dd($data);
    //             if($key == 0){
    //                 $header = $data[0];
    //                 unset($data[0]);
    //                 $newHeader = ['country_cat','created_by','company_id'];
    //                 $header = array_merge($header,$newHeader);
    //             }

    //             $header = array_map(function($value){
    //                 if($value == 'indicator'){
    //                     return 'indicator_id';
    //                 }
    //                 return $value;
    //             },$header);

    //             //Replace Indicator with Indicator_Id
    //             foreach($data as &$row){
    //                 //Indicator
    //                 $indicatorName = $row[0];
    //                 $indicator = Indicator::where('variablename',$indicatorName)->pluck('id')->first();

    //                 if($indicator){
    //                     $row[0] = $indicator;
    //                 }else{
    //                     return redirect()->back()->with('error',$indicatorName.' Not Found');
    //                 }                    

    //                 //Color
    //                 $color = $row[5];
    //                 $colorCategory = CategoryColor::where('subcountry_leg_col',$color)->pluck('category')->first();

    //                 if($colorCategory){
    //                     $row[7] = $colorCategory;
    //                 }else{
    //                     return redirect()->back()->with('error',$indicatorName.' Not Found'); 
    //                 }

    //                 $row[8] = auth()->user()->id;
    //                 $row[9] = auth()->user()->company_id;
    //             }

    //             $batch->add(new CountryCSVData($header,$data));
    //         }
    //     }

    //     $countriesData = CountryData::with(['indicator','country','user'])->paginate(10);

    //     return redirect()->route('admin.country-data.index',compact('countriesData'))->with('success','CSV import added on queue. Will update you once done!!!');
    // }

    public function bulkInsert(Request $request)
    {
        try {

            $validatedData = $request->validate([
                'csv_file' => 'required|file|mimes:csv|max:500000'
            ]);

            $errors = [];

            $countriesData = CountryData::with(['indicator', 'country', 'user'])->paginate(10);

            if ($request->has('csv_file')) {
                //Load CSV file
                $csv = file($request->csv_file);
                $data = array_map('str_getcsv', $csv);

                $header = [];
                $batch = Bus::batch([])->dispatch();

                foreach ($data as $key => $row) {
                    if ($key == 0) {
                        $header = $row;
                        $newHeader = ['country_cat', 'created_by', 'company_id'];
                        $header = array_merge($header, $newHeader);
                        continue;
                    }
                    $countryCode = $row[1];

                    try {
                        //Replace Indicator to IndicatorId
                        $indicatorName = $row[0];
                        $indicator = Indicator::where('variablename', $indicatorName)->pluck('id')->first();

                        if ($indicator) {
                            $row[0] = $indicator; //Replace indicator name with its id
                        } else {
                            $errors[$countryCode][] = 'No Indicator as ' . $indicatorName. '. Excel File Row: '.($key+1);
                            continue;
                        }

                        //Color
                        $color = $row[5];
                        $colorCategory = CategoryColor::where('subcountry_leg_col', $color)->pluck('category')->first();

                        if ($colorCategory) {
                            $row[7] = $colorCategory;
                        } else {
                            $errors[$countryCode][] = 'No Sub Color as ' . $color.'. Excel File Row: '.($key+1);
                            continue;
                        }

                        //Add user and company Id to the new
                        $row[8] = auth()->user()->id;
                        $row[9] = auth()->user()->company_id;

                        
                        $groupedByCountry[$countryCode][] = $row;                        
                    } catch (\Exception $e) {
                        $errors[$row[1]][] = 'Error processing row: '.$e->getMessage();
                    }
                }

                //Process each country data in a separate batch
                foreach ($groupedByCountry as $countryCode => $countryData) {
                    $batch->add(new CountryCSVData($header, $countryData, $countryCode));
                }

                if(!empty($errors)){
                    //Log the errors for each country
                    foreach($errors as $countryCode => $countryErrors){
                        Log::channel('country_data_log')->error('CSV Import Errors for Country ('.$countryCode.'): '."\n".implode("\n",$countryErrors));

                        //Send Email With the Errors for the specific  country
                        Mail::to('anupshk7@gmail.com')->send(new CountryDataErrorNotication(
                            $countryErrors,
                            $countryCode
                        ));
                    }
                    
                    return redirect()->route('admin.country-data.index', compact('countriesData'))->with('error', 'Error');
                }

                return redirect()->route('admin.country-data.index', compact('countriesData'))->with('success', 'CSV import added on queue. Will update you once done!!!');
            }
        } catch (\Exception $e) {
            Log::channel('country_data_log')->error('Error occured during bulk insert: ' . $e->getMessage());
            return redirect()->route('admin.country-data.index', compact('countriesData'))->with('error', 'Error occurred during import. Please try again later');
        }
    }
}
