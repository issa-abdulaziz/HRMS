<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use DateTime;

class Shift extends Model
{    
    public $timestamps = false;
    
    public function employees() {
        return $this->hasMany(Employee::class);
    }
    public function getWorkingHour() {
        $starting_time = new DateTime($this->starting_time);
        $leaving_time = new DateTime($this->leaving_time);
        $time_diff = $starting_time->diff($leaving_time);
        return $time_diff->h + $time_diff->i /60;
    }
}
