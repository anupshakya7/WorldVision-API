<?php
namespace App\Helpers;

use InvalidArgumentException;

class DomainPercentage{
    public static function domainCalculation($firstYear,$currentYear){
        if($firstYear == 0 || $currentYear == 0){
            // throw new InvalidArgumentException("Value cannot be Zero");
            return 0;
        }

        //Calculate the Domain Percentage Change
        $percentageChange = (($currentYear - $firstYear)/$firstYear)*100;

        return $percentageChange;
    }
}
?>