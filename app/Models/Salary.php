<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    public function employee() {
        return $this->belongsTo(Employee::class);
    }
}
