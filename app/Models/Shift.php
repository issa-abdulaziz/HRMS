<?php

namespace App\models;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    public $timestamps = false;
    protected $guarded = [];
    protected $appends = ['working_hour'];

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
    public function getWorkingHourAttribute() {
        $diffInMin = calculateDiffBtw2TimeString($this->starting_time, $this->leaving_time, $this->across_midnight);
        return number_format($diffInMin / 60, 2);
    }
}
