<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Overtime extends Model
{
    public $timestamps = false;

    public function employee() {
        return $this->belongsTo(Employee::class);
    }
    
    public function scopeFilter($query, $params)
    {
        if ( isset($params['date']) && trim($params['date'] !== '') ) {
            $query->where('date', 'LIKE', trim($params['date']) . '%');
        } 
        elseif (! isset($params['date'])) {
            $query->where('date', 'LIKE', date('Y') . '-' . date('m') . '%');
        }
        return $query;
    }
    public static function getTotalOvertimeAmount($month) {
        return Overtime::where('date', 'like', $month . '%')->sum('amount');
    }
}
