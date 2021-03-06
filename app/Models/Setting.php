<?php

namespace App\models;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    public $timestamps = false;
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function getRate($date)
    {
        return Carbon::parse($date)->format('l') == $this->weekend ? $this->weekend_overtime_rate : $this->normal_overtime_rate;
    }
}
