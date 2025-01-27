<?php

namespace App\Jobs;

use App\Models\Admin\SubCountryData;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SubCountryCSVData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels,Batchable;

    public $header;
    public $data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($header,$data)
    {
        $this->header = $header;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach($this->data as $subCountryData){
            $subcountryDataInput = array_combine($this->header,$subCountryData);
            SubCountryData::create($subcountryDataInput);
        }
    }
}
