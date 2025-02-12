<?php

namespace App\Jobs;

use App\Mail\CountryDataErrorNotification;
use App\Models\Admin\Indicator;
use App\Models\Admin\Source;
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
    public function __construct($header,$data,$geocode,$userId,$companyId)
    {
        $this->header = $header;
        $this->data = $data;
        $this->geocode = $geocode;
        $this->userId = $userId;
        $this->companyId = $companyId;
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
            Log::channel('sub_country_data_log')->info('Header: '.json_encode($this->header));
            Log::channel('sub_country_data_log')->info('Data: '.json_encode($this->data));
            Log::channel('sub_country_data_log')->info('Error: '.json_encode($this->errors));

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
            Log::channel('sub_country_data_log')->info('Insert Data[]: '.json_encode($insertData));

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
            // Mail::to('anupshk7@gmail.com')
            //     ->send(new CountryDataErrorNotification($this->geocode,$e->getMessage()));
        }
        foreach($this->data as $subCountryData){
            $subcountryDataInput = array_combine($this->header,$subCountryData);
            SubCountryData::create($subcountryDataInput);
        }
    }

    //Data Formatting
    public function subCountryDataFormatting($header,$data){
        $newHeader = ['created_by','company_id','created_at','updated_at'];
        Log::channel('sub_country_data_log')->info('1');

        //Header Formatting
        $this->header = array_merge($header,$newHeader);
        Log::channel('sub_country_data_log')->info('2');
        
        $this->header = array_map(function($value){
            if($value == 'indicator'){
                return 'indicator_id';
            }
            if($value == 'source'){
                return 'source_id';
            }
            return $value;
        },$this->header);

        Log::channel('sub_country_data_log')->info('3');
        Log::channel('sub_country_data_log')->info('3 Header'.json_encode($this->header));

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

            $bandedValue = $row[4];
            if($bandedValue > $maxValue || $bandedValue < $minValue){
                $bandedValue = 0.0;
                $row[4] = $bandedValue;
            }

            //Source
            $sourceName = $row[7];
            $source = Source::where('source',$sourceName)->pluck('id')->first();
            Log::channel('sub_country_data_log')->info('4');

            if($source){
                $row[7] = $source;
            }else{
                $sourceNew = Source::create([
                    'source'=>$sourceName,
                    'created_by'=>$this->userId,
                    'company_id'=>$this->companyId
                ]);
                $row[7] = $sourceNew->id;
                // Mail::to('anupshk7@gmail.com')
                //     ->send(new CountryDataErrorNotification($this->geocode,'Since the source does not exist, we have created new records as '.$sourceName.'.'));
                Log::channel('sub_country_data_log')->info('4');
                Log::channel('sub_country_data_log')->info('4: '.$sourceNew);
            }
            

            $row[9] = $this->userId;
            $row[10] = $this->companyId;
            $row[11] = now()->format('Y-m-d H:i:s');
            $row[12] = now()->format('Y-m-d H:i:s');
            Log::channel('sub_country_data_log')->info('6');
        }
        $this->data = $data;
        // Log::channel('sub_country_data_log')->info('Data:'.json_encode($this->data));
    }
}
