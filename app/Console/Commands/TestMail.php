<?php

namespace App\Console\Commands;

use App\Mail\AcledErrorNotification;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class TestMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This is a Command to test mail.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try{
            Mail::to('anupshk7@gmail.com')->cc('anupshk39@gmail.com')->send(new AcledErrorNotification(
                'Test Mail',
                'For Testing Purpose',
                'onLY FOR TEsting'
            ));
            Log::channel('acled_data_log')->info('Test Email sent successfully!!!');
        }catch(Exception $e){
            Log::channel('acled_data_log')->error('Failed to Send Test Email: '.$e->getMessage());
        }
    }
}
