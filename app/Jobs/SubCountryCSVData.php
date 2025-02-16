<?php

namespace App\Jobs;

use App\Mail\CountryDataErrorNotification;
use App\Models\Admin\Indicator;
use App\Models\Admin\Source;
use App\Models\Admin\SubCountry;
use App\Models\Admin\SubCountryData;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SubCountryCSVData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels,Batchable;

    public $header;
    public $data;
    public $errors;
    public $geocode;
    public $userId;
    public $companyId;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($header,$data,$geocode)
    {
        $this->header = $header;
        $this->data = $data;
        $this->geocode = $geocode;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try{
            DB::beginTransaction(); //Start the transaction

            //Sub Country Data Formatting
            $this->subCountryDataFormatting($this->header,$this->data);

            //Check for errors
            if(!empty($this->errors)){
                throw new \Exception("\n".implode("\n",$this->errors));
            }

            //Array for bulk insert
            $insertData = [];
            foreach($this->data as $stateData){
                $stateDataInput = array_combine($this->header,$stateData);
                $insertData[] = $stateDataInput;
            }

            //Insert all data in bulk
            SubCountryData::insert($insertData);
            Log::channel('sub_country_data_log')->info('Successfully Inserted Data for '.$this->geocode);
            
            //Commit the transaction
            DB::commit();
        }catch(\Exception $e){
            //Rollback if something goes wrong
            DB::rollback();

            //Log
            Log::channel('sub_country_data_log')->error('Error processing CSV data: '.$e->getMessage());

            //Mail for Error Notification
            Mail::to('anupshk7@gmail.com')
                ->send(new CountryDataErrorNotification($this->geocode,$e->getMessage()));
        }
    }

    //Data Formatting
    public function subCountryDataFormatting($header,$data){
        $this->header = array_map(function($value){
            if($value == 'indicator'){
                return 'indicator_id';
            }
            if($value == 'source'){
                return 'source_id';
            }
            return $value;
        },$this->header);

        //Error Array
        $this->errors = [];

        $maxValue = 2147483647;
        $minValue = -2147483648;

        //Data Formatting
        foreach($data as &$row){
            //Geocode
            $geocode = $this->geocode;
            
            //Year
            $year = $row[2];

            //Indicator
            $indicatorName = $row[0];

            $indicator = Indicator::where('variablename',$indicatorName)->pluck('id')->first();

            if($indicator){
                $row[0] = $indicator;
            }else{
                $this->errors[] = 'Indicator does not exists with the name '.$indicatorName.' for '.$geocode.' ('.$indicatorName.' ,'.$year.')';
            }

            $subcountryName = $row[1];
            $subcountry = SubCountry::where('geocode',$subcountryName)->first();

            if(!$subcountry){
                $this->errors[] = 'State does not exists with the code '.$subcountryName.' ('.$indicatorName.' ,'.$year.')';
            }

            $bandedValue = $row[4];
            if($bandedValue > $maxValue || $bandedValue < $minValue){
                $bandedValue = 0.0;
                $row[4] = $bandedValue;
            }

            //Source
            $sourceName = $row[7];
            $source = Source::where('source',$sourceName)->pluck('id')->first();

            if($source){
                $row[7] = $source;
            }else{
                $sourceNew = Source::create([
                    'source'=>$sourceName,
                    'created_by'=>$row[9],
                    'company_id'=>$row[10]
                ]);

                $row[7] = $sourceNew->id;
                
                Log::channel('sub_country_data_log')->info('Since the source does not exist, we have created new records as '.$sourceName.'.');

                Mail::to('anupshk7@gmail.com')
                    ->send(new CountryDataErrorNotification($this->geocode,'Since the source does not exist, we have created new records as '.$sourceName.'.'));
            }
        }

        $this->data = $data;
    }
}
