<?php

namespace App\Console\Commands;

use App\Helpers\AcledHelper;
use App\Mail\AcledErrorNotification;
use App\Models\Acled\Acled;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AcledMissingDataFetch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:acled-missing-data';

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
    public function handle()
    {
        $existingEventDates = Acled::distinct()->pluck('event_date')->toArray();

        $startDate = Carbon::createFromFormat('Y-m-d',config('missingevent.missing_start_date'));
        $endDate = Carbon::now();

        $datesBetween = [];
        for($date = $startDate; $date->lte($endDate);$date->addDay()){
            $datesBetween[] = $date->format('Y-m-d');
        }

        $missingDate = array_diff($datesBetween,$existingEventDates);

        //Setting Start Missing Date to Config File
        AcledHelper::MissingStartDate(reset($missingDate));

        $chunkSize = 1;
        $dateChunks = array_chunk($missingDate,$chunkSize);
        
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
                    Log::channel('acled_data_log')->info('Fetched API Data For: '.$datesString.'. Count: '.$updateDatas['count']);

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
                            'error'=>Str::limit($e->getMessage(),500),
                            'date'=>$datesString
                        ]);

                        Mail::to('web@krizmatic.com')
                        ->cc(['dev2@krizmatic.com','anupshk39@gmail.com'])
                        ->send(new AcledErrorNotification(
                            'Error when inserting data batch',
                            Str::limit($e->getMessage(),500),
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
                    'error'=>Str::limit($e->getMessage(),500),
                    'url'=>$fullUrl
                ]);

                Mail::to('web@krizmatic.com')
                ->cc(['dev2@krizmatic.com','anupshk39@gmail.com'])        
                ->send(new AcledErrorNotification(
                    'Error making API request',
                    Str::limit($e->getMessage(),500),
                    $fullUrl
                ));
            }

            sleep(2);
        }
        
    }
}
