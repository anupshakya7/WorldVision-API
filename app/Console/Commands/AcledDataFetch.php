<?php

namespace App\Console\Commands;

use App\Mail\AcledErrorNotification;
use App\Models\Acled\Acled;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AcledDataFetch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:acled-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch data for acled from its API';

    /**
     * Execute the console command.
     *
     * @return int
     */
    // public function handle()
    // {
    //     $latestDBDate = Acled::orderBy('event_date','desc')->pluck('event_date')->first(); 
    //     $todayDate = Carbon::now()->format('Y-m-d');

    //     //Convert dates into Carbon instances
    //     $latestDBDate = Carbon::parse($latestDBDate)->addDay();
    //     $todayDate = Carbon::parse($todayDate);

    //     //All dates between two dates
    //     $datesBetween = [];

    //     for($date = $latestDBDate;$date->lte($todayDate);$date->addDay()){
    //         $datesBetween[] = $date->format('Y-m-d');
    //     }

    //     $chunkSize = 2;
    //     $dateChunks = array_chunk($datesBetween,$chunkSize);

    //     foreach($dateChunks as $chunk){
    //         $datesString = implode('|',$chunk);

    //         $baseUrl = 'https://api.acleddata.com/acled/read';
    //         $params = [
    //             'key'=>'IqghCdF0UaC1m3NiHlLe',
    //             'email'=>'info@economicsandpeace.org',
    //             'limit'=>0,
    //             'event_date'=>$datesString
    //         ];

    //         $queryString = http_build_query($params);

    //         $fullUrl = $baseUrl.'/?'.$queryString;

            
    //         //Http Request for API Data
    //         try{
    //         $response = Http::retry(3,2000)->timeout(180)->get($fullUrl);

    //         if($response->successful()){
    //             $updateDatas = $response->json();
                
    //             Log::channel('acled_data_log')->info('Data: ',$updateDatas);

    //             foreach($updateDatas['data'] as $updateData){
    //                 if(!is_array($updateData)){
    //                     $updateData = (array) $updateData;
    //                 }

    //                 $formattedDate = Carbon::createFromTimestamp($updateData['timestamp'])->toDateTimeString();

    //                 $updateData['timestamp'] = $formattedDate;
                    
    //                 try{
    //                     Acled::create($updateData);
    //                     Log::channel('acled_data_log')->info('Successfully inserted data:',$updateData);
    //                 }catch(\Exception $e){
    //                     Log::channel('acled_data_log')->error('Error inserting data:',[
    //                         'error'=>$e->getMessage(),
    //                         'data'=>$updateData
    //                     ]);
    //                 } 
    //             }
    //         }else{
    //             $this->error('Failed to fetch data from the API.');
    //             Log::channel('acled_data_log')->error('API Request failed',['url'=>$fullUrl,'status'=>$response->status()]);
    //         }
    //     }catch(\Exception $e){
    //         //Handle failure to make the API request (netwoek issues, etc.)
    //         $this->error('Error making API request: '.$e->getMessage());
    //         Log::channel('acled_data_log')->error('Error making API request',['exception'=>$e->getMessage(),'url'=>$fullUrl]);
    //     }
        
    //     sleep(2);
    //     }
    // }

    public function handle(){
        $latestDBDate = Acled::orderBy('event_date','desc')->pluck('event_date')->first();
        $todayDate = Carbon::now()->format('Y-m-d');

        //Convert dates into Carbon instances
        $latestDBDate = Carbon::parse($latestDBDate)->addDay();
        $todayDate = Carbon::parse($todayDate);

        //All dates between two dates
        $datesBetween = [];

        for($date = $latestDBDate; $date->lte($todayDate);$date->addDay()){
            $datesBetween[] = $date->format('Y-m-d');
        }

        $chunkSize = 1;
        $dateChunks = array_chunk($datesBetween,$chunkSize);

        foreach($dateChunks as $chunk){
            $datesString = implode('|',$chunk);

            $baseUrl = 'https://api.acleddata.com/acled/read';
            $params = [
                'key'=>'IqghCdF0UaC1m3NiHlLe',
                'email'=>'info@economicsandpeace.org',
                'limit'=>0,
                'event_date'=>$datesString
            ];

            $queryString = http_build_query($params);

            $fullUrl = $baseUrl.'/?'.$queryString;

            //Http Request for API Data
            try{
                $response = Http::retry(3,2000)->timeout(180)->get($fullUrl);

                if($response->successful()){
                    $updateDatas = $response->json();

                    //Log the fetched data
                    Log::channel('acled_data_log')->info('Fetched Data: ',$updateDatas);

                    //Array for batch insert
                    $dataToInsert = [];

                    foreach($updateDatas['data'] as $updateData){
                        if(!is_array($updateData)){
                            $updateData = (array) $updateData;
                        }

                        $formattedDate = Carbon::createFromTimestamp($updateData['timestamp'])->toDateString();
                        $updateData['timestamp'] = $formattedDate;

                        //Add the formatted data to batch insert array
                        $dataToInsert[] = $updateData;
                    }

                    //Begin transaction for bulk insert
                    DB::beginTransaction();

                    try{
                        if(!empty($dataToInsert)){
                            Acled::insert($dataToInsert);
                            Log::channel('acled_data_log')->info('Successfully inserted data batch for '.$datesString);
                        }
                        DB::commit();                  
                    }catch(\Exception $e){
                        DB::rollBack();
                        Log::channel('acled_data_log')->error('Error when inserting data batch',[
                            'error'=>$e->getMessage(),
                            'date'=>$datesString
                        ]);

                        Mail::to('anupshk7@gmail.com')->send(new AcledErrorNotification(
                            'Error when inserting data batch',
                            $e->getMessage(),
                            'Error when inserting data batch for '.$datesString
                        ));
                    }
                }else{
                    Log::channel('acled_data_log')->error('API Request failed',[
                        'url'=>$fullUrl,
                        'status'=>$response->status()
                    ]);
                }
            }catch(\Exception $e){
                Log::channel('acled_data_log')->error('Error making API request',[
                    'error'=>$e->getMessage(),
                    'url'=>$fullUrl
                ]);

                Mail::to('anupshk7@gmail.com')->send(new AcledErrorNotification(
                    'Error making API request',
                    $e->getMessage(),
                    $fullUrl
                ));
            }

            //Sleep between chunks to prevent hitting rate limits
            sleep(2);
        }
    }
}
