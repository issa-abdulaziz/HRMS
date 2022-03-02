<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Vacation extends Model
{
    public $timestamps = false;
    
    public function employee() {
        return $this->belongsTo(Employee::class);
    }
    
    public function scopeFilter($query, $params)
    {
        if ( isset($params['date']) && trim($params['date'] !== '') ) {
            $query->where('date_from', 'LIKE', trim($params['date']) . '%')->orwhere('date_to', 'LIKE', trim($params['date']) . '%');
        } 
        elseif (! isset($params['date'])) {
            $query->where('date_from', 'LIKE', date('Y') . '-' . date('m') . '%')->orwhere('date_to', 'LIKE', date('Y') . '-' . date('m') . '%');
        }
        return $query;
    }
    public function diffBtwDate($date1, $date2) {
        $date1 = $this->date_from;
        $date2 = $this->date_to;
        $diff = strtotime($date1) - strtotime($date2);
        return ceil(abs($diff / 86400)) + 1;
    }
}
