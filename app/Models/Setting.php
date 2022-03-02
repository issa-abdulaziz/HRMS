<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    public $timestamps = false;
    
    public function getRate($date) {        
        //Convert the date string into a unix timestamp.
        $unixTimestamp = strtotime($date);
        //Get the day of the week using PHP's date function.
        $day_of_week = date("l", $unixTimestamp);

        return $day_of_week === $this->weekend ? $this->weekend_overtime_rate : $this->normal_overtime_rate;        
    }
}
