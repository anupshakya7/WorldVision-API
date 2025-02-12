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
            
            //World Vision
            $this->countryWorldVisionFormatting($this->header,$this->data);

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

    //Data Formatting
    public function countryWorldVisionFormatting($header,$data){
        $newHeader = ['country_cat','created_by','company_id','created_at','updated_at'];
        
        //Header Formatting
        $this->header = array_merge($header,$newHeader);
        
        $this->header = array_map(function($value){
            if($value == 'indicator'){
                return 'indicator_id';
            }
            return $value;
        },$this->header);

        //Error Array
        $this->errors = [];

        //Data Formatting
        foreach($data as &$row){
            //Country Code
            $countrycode = $this->countrycode;

            //Year
            $year = $row[2];

            //Indicator
            $indicatorName = $row[0];
            
            $indicator = Indicator::where('variablename',$indicatorName)->pluck('id')->first();

            if($indicator){
                $row[0] = $indicator;
            }else{
                $row[0] = null;
                $this->errors[] = 'Indicator does not exists with the name '.$indicatorName.' for '.$countrycode.' ('.$indicatorName.' ,'.$year.')'; 
            }

            //Color
            $color = $row[5];
            $colorCategory = CategoryColor::where('subcountry_leg_col',$color)->pluck('category')->first();

            if($colorCategory){
                $row[7] = $colorCategory;
            }else{
                $row[7] = null;
                $this->errors[] = 'Color Category does not exits with the code '.$color.' for '.$countrycode.' ('.$indicatorName.' ,'.$year.')';
            }

            $row[8] = $this->userId;
            $row[9] = $this->companyId;
            $row[10] = now()->format('Y-m-d H:i:s');
            $row[11] = now()->format('Y-m-d H:i:s');
        }

        $this->data = $data;
    }
}
