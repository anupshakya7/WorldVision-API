<?php

namespace App\Console\Commands;

use App\Models\Acled\Acled;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
    public function handle()
    {
        $latestDBDate = Acled::orderBy('event_date','desc')->pluck('event_date')->first(); 
        $todayDate = Carbon::now()->format('Y-m-d');

        //Convert dates into Carbon instances
        $latestDBDate = Carbon::parse($latestDBDate)->addDay();
        $todayDate = Carbon::parse($todayDate);

        //All dates between two dates
        $datesBetween = [];

        for($date = $latestDBDate;$date->lte($todayDate);$date->addDay()){
            $datesBetween[] = $date->format('Y-m-d');
        }

        $chunkSize = 2;
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
            $response = Http::retry(3,2000)->timeout(180)->get($fullUrl);

            if($response->successful()){
                $updateDatas = $response->json();
                
                Log::info('Data: ',$updateDatas);

                foreach($updateDatas['data'] as $updateData){
                    if(!is_array($updateData)){
                        $updateData = (array) $updateData;
                    }

                    $formattedDate = Carbon::createFromTimestamp($updateData['timestamp'])->toDateTimeString();

                    $updateData['timestamp'] = $formattedDate;
                    
                    Acled::create($updateData);
                }
            }else{
                $this->error('Failed to fetch data from the API.');
            }

            sleep(2);
        }
    }
}
