<?php

use Carbon\Carbon;

if (!function_exists('formattedDate')) {
    function formattedDate($date)
    {
 
        $formattedDate = Carbon::parse($date)->isoFormat('D MMM YYYY', 'id');


        return $formattedDate;
    }
}