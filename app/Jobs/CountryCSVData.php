<?php

namespace App\Jobs;

use App\Models\Admin\CountryData;
use Exception;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CountryCSVData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels,Batchable;

    public $header;
    public $data;
    public $countryCode;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($header,$data,$countryCode)
    {
        $this->header = $header;
        $this->data = $data;
        $this->countryCode = $countryCode;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //Array for bulk insert
        $bulkData = [];

        //Start DB Trasaction
        DB::beginTransaction();
    
        try{
            foreach($this->data as $countryData){
                //Combine header and row into associative array
                $countryDataInput = array_combine($this->header,$countryData);
               
                if(isset($countryDataInput['indicator'])){
                    $countryDataInput['indicator_id'] = $countryData[0];
                    unset($countryDataInput['indicator']); //Remove the old indicator key for the inserting in format
                }
                
                $countryDataInput['indicator_id'] = $countryData[0];

                //Add timestamps if needed
                $countryDataInput['created_at'] = now();
                $countryDataInput['updated_at'] = now();

                //Add each row to the bulk data array
                $bulkData[] = $countryDataInput;
            }

            if(!empty($bulkData)){
                CountryData::insert($bulkData);
                Log::channel('country_data_log')->info('Successfully Inserted Data for '.$this->countryCode);
            }

            DB::commit();
        }catch(\Exception $e){
            DB::rollBack();

            //Log Error
            Log::channel('country_data_log')->error('Error processing country data for '.$this->countryCode.' .Error: '.$e->getMessage().'');

            throw $e;
        }  

        // foreach($this->data as $countryData){
        //     $countryDataInput = array_combine($this->header,$countryData);
        //     CountryData::create($countryDataInput);
        // }
    }
}
