<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    public $timestamps = false;
    protected $guarded = [];

    public function employee() {
        return $this->belongsTo(Employee::class);
    }
}
