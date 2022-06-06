<?php

namespace App\models;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use DateTime;

class Shift extends Model
{
    public $timestamps = false;
    protected $guarded = [];

    public function getStartingTimeAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('H:i');
    }

    public function getLeavingTimeAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('H:i');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
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
