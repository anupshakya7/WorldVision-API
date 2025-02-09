<?php
namespace App\Helpers;

class AcledHelper{
    public static function MissingStartDate($startDate){
        $configPath = config_path('missingevent.php');

        //Get the current config array
        $config = include $configPath;

        //Update the dynamic start date value
        $config['missing_start_date'] = $startDate;

        //Write back to the config file
        file_put_contents($configPath,'<?php return '.var_export($config,true).';'.PHP_EOL);
    }
}
?>