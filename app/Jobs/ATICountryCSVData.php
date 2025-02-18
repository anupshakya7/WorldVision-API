<?php

namespace App\Jobs;

use App\Mail\CountryDataErrorNotification;
use App\Models\Admin\CategoryColor;
use App\Models\Admin\CountryData;
use App\Models\Admin\Indicator;
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

class ATICountryCSVData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $header;
    public $data;
    public $errors;
    public $countrycode;
    public $batchId;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($header,$data,$countrycode)
    {
        $this->header = $header;
        $this->data = $data;
        $this->countrycode = $countrycode;
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
            
            //World Vision
            $this->dataformatting($this->header,$this->data);

            //Check for errors
            if(!empty($this->errors)){
                throw new \Exception("\n".implode("\n",$this->errors));
            }

            //Array for bulk insert
            $insertData = [];
            foreach($this->data as $countryData){
                $countryDataInput = array_combine($this->header,$countryData);
                $insertData[] = $countryDataInput;
            }

            //Insert all data in bulk
            CountryData::insert($insertData);
            Log::channel('country_data_log')->info('Successfully Inserted Data for '.$this->countrycode);

            //Commit the transaction
            DB::commit();

        }catch(\Exception $e){
            //Rollback if something goes wrong
            DB::rollback();

            //Log
            Log::channel('country_data_log')->error('Error processing CSV data: '.$e->getMessage());

            // Mail for Error Notification
            Mail::to('anupshk7@gmail.com')
                ->send(new CountryDataErrorNotification($this->countrycode,$e->getMessage()));
        }
    }
    
    public function withBatchId($batchId){
        $this->batchId = $batchId;
        return $this;
    }

    //Data Formatting
    public function dataformatting($header,$data){
        $this->header = array_map(function($value){
            if($value == 'indicator'){
                return 'indicator_id';
            }
            return $value;
        },$this->header);

        //Find Indicator in Header
        $indicatorIndex = array_search("indicator",$header);

        //Find Year in Header
        $yearIndex = array_search("year",$header);

        //Error Array
        $this->errors = [];

        //Data Formatting
        foreach($data as &$row){
            //Country Code
            $countrycode = $this->countrycode;

            //Indicator
            $indicatorName = $indicatorIndex !== false ? $row[$indicatorIndex]:'';
            //Year
            $year = $yearIndex !== false ? $row[$yearIndex]:'';

            if($indicatorName !== ''){
                $indicator = Indicator::where('variablename',$indicatorName)->pluck('id')->first();

                if($indicator){
                    $row[$indicatorIndex] = $indicator;
                }else{
                    $row[$indicatorIndex] = null;
                    $year = $year !== '' ? ' ,'.$year :'';
                    $this->errors[] = 'Indicator does not exists with the name '.$indicatorName.' for '.$countrycode.' ('.$indicatorName.''.$year.')'; 
                }

            }
        }

        $this->data = $data;
    }
}
