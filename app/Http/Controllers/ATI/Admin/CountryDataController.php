<?php

namespace App\Http\Controllers\ATI\Admin;

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
    //Export csv file
    public function generateCSV()
    {
        $countriesData = CountryData::with(['indicator', 'country', 'user'])->get();
        $filename = "country-data.csv";
        $fp = fopen($filename, 'w+');
        fputcsv($fp, array('ID', 'Indicator', 'Country', 'Country Code', 'Year', 'Country Score', 'Country Color', 'Country Cateory', 'Created By', 'Created At'));

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
                $row->user->name,
                $row->created_at
            ));
        }

        fclose($fp);
        $headers = array('Content-Type' => 'text/csv');

        return response()->download($filename, 'country-data.csv', $headers);
    }

    //Bulk Import
    public function bulk($slug)
    {
        return view('ati.admin.dashboard.country_data.bulk', compact('slug'));
    }

    public function bulkInsert(Request $request)
    {
        $validatedData = $request->validate([
            'type' => 'required|string',
            'csv_file' => 'required|file|mimes:csv|max:500000'
        ]);

        $data_type = $request->type;
        $custom_data = [auth()->user()->id, auth()->user()->company_id];
        if ($data_type == 'election') {
            $route = 'admin.ati.elections.index';
            $countriesData = CountryData::with(['country', 'user'])->filterElectionData()->paginate(10);
            $custom_data[2] = 0;   
        } elseif ($data_type == 'disruption') {
            $route = 'admin.ati.disruptions.index';
            $countriesData = CountryData::with(['country', 'user'])->filterHistoricalDisruptionData()->paginate(10);
            $custom_data[2] = 1;
        } elseif ($data_type == 'indicator-score') {
            $route = 'admin.ati.indicator-score.index';
            $countriesData = CountryData::with(['indicator', 'country', 'user'])->filterIndicatorScore()->paginate(10);
            $custom_data[2] = 2;
        }elseif ($data_type == 'voice-people') {
            $route = 'admin.ati.voice-people.index';
            $countriesData = CountryData::with(['indicator', 'country', 'user'])->filterIndicatorScore()->paginate(10);
            $custom_data[2] = 3;
        }elseif ($data_type == 'domain-score') {
            $route = 'admin.ati.domain-score.index';
            $countriesData = CountryData::with(['indicator', 'country', 'user'])->filterIndicatorScore()->paginate(10);
            $custom_data[2] = 4;
        }
        $countriesData = PaginationHelper::addSerialNo($countriesData);

        if ($request->has('csv_file')) {
            $csv = file($request->csv_file);
            $chunks = array_chunk($csv, 500);
            $header = [];
            $batch = Bus::batch([])->dispatch();
            $custom_header = ['created_by', 'company_id', 'political_context'];

            foreach ($chunks as $key => $chunk) {

                $data = array_map('str_getcsv', $chunk);

                if ($key == 0) {
                    $header = $data[0];
                    $header = array_merge($header, $custom_header);
                    unset($data[0]);
                }

                // Add custom data to each row in $data
                foreach ($data as &$row) {
                    if($data_type == 'election'){
                        if($row[1]==="" ){
                            $row[1]=null;
                        }
                    }
                    // Append custom data values as indexed values
                    $row = array_merge($row, $custom_data);
                }
                $batch->add(new CountryCSVData($header, $data));
            }
        }

        return redirect()->route($route, compact('countriesData'))->with('success', 'CSV import added on queue. Will update you once done!!!');
    }
}
