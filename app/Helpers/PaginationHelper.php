<?php
namespace App\Helpers;

use Illuminate\Pagination\LengthAwarePaginator;

class PaginationHelper{
    public static function addSerialNo(LengthAwarePaginator $paginator){
        $paginator->getCollection()->transform(function($item,$key) use($paginator){
            $item->serial_no = $paginator->firstItem() + $key;
            return $item;
        });
        return $paginator;
    }
}

?>