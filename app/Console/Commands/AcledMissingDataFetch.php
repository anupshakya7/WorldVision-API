<?php

namespace App\Console\Commands;

use App\Models\Acled\Acled;
use Carbon\Carbon;
use Illuminate\Console\Command;

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

        $startDate = Carbon::createFromFormat('Y-m-d','2025-01-17');
        $endDate = Carbon::now();

        $datesBetween = [];
        for($date = $startDate; $date->lte($endDate);$date->addDay()){
            $datesBetween[] = $date->format('Y-m-d');
        }

        $missingDate = array_diff($datesBetween,$existingEventDates);
        dd($missingDate);
    }
}
