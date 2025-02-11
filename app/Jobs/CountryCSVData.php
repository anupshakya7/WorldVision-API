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

class CountryCSVData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels,Batchable;

    public $header;
    public $data;
    public $errors;
    public $countrycode;
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
            Log::channel('country_data_log')->info('a');
            //World Vision
            Log::channel('country_data_log')->info('a'.json_encode($this->header));
            Log::channel('country_data_log')->info('a'.json_encode($this->data));
            $this->countryWorldVisionFormatting($this->header,$this->data);
            Log::channel('country_data_log')->info('b');

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
            Log::channel('country_data_log')->info('c');

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

            //Mail for Error Notification
            // Mail::to('anupshk7@gmail.com')
            //     ->send(new CountryDataErrorNotification($this->countrycode,$e->getMessage()));
        }
       
    }

    //Data Formatting
    public function countryWorldVisionFormatting($header,$data){
        Log::channel('country_data_log')->info('1');
        $newHeader = ['country_cat','created_by','company_id','created_at','updated_at'];
        
        Log::channel('country_data_log')->info('2');
        //Header Formatting
        $this->header = array_merge($header,$newHeader);
        
        Log::channel('country_data_log')->info('3');
        $this->header = array_map(function($value){
            if($value == 'indicator'){
                return 'indicator_id';
            }
            return $value;
        },$this->header);

        Log::channel('country_data_log')->info('4');
        //Error Array
        $this->errors = [];

        //Data Formatting
        foreach($data as &$row){
            //Country Code
            $countrycode = $this->countrycode;

            //Year
            $year = $row[2];

            //Indicator
            Log::channel('country_data_log')->info('Indicator Query: ' . json_encode($row));
            $indicatorName = $row[0];
            
            $indicator = Indicator::where('variablename',$indicatorName)->first();

            Log::channel('country_data_log')->info('Indicator Query: ');

            if($indicator){
                $row[0] = $indicator->id;
            }else{
                $row[0] = null;
                $this->errors[] = 'Indicator does not exists with the name '.$indicatorName.' for '.$countrycode.' ('.$indicatorName.' ,'.$year.')'; 
            }
            Log::channel('country_data_log')->info('Processing Color: ' . $row[5]);
            Log::channel('country_data_log')->info('5');
            //Color
            $color = $row[5];
            $colorCategory = CategoryColor::where('subcountry_leg_col',$color)->pluck('category')->first();

            if($colorCategory){
                $row[7] = $colorCategory;
            }else{
                $row[7] = null;
                $this->errors[] = 'Color Category does not exits with the code '.$color.' for '.$countrycode.' ('.$indicatorName.' ,'.$year.')';
            }

            Log::channel('country_data_log')->info('6');

            $row[8] = auth()->user()->id;
            $row[9] = auth()->user()->company_id;
            $row[10] = now();
            $row[11] = now();
        }
        Log::channel('country_data_log')->info(json_encode($data));

        $this->data = $data;
    }
}
