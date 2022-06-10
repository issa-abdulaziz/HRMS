<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class AdvancedPayment extends Model
{
    public $timestamps = false;
    protected $guarded = [];

    public function employee() {
        return $this->belongsTo(Employee::class);
    }

    public function scopeFilter($query, $params)
    {
        if ( isset($params['date']) && trim($params['date'] !== '') ) {
            $query->where('date', 'LIKE', trim($params['date']) . '%');
        }
        elseif (! isset($params['date'])) {
            $query->where('date', 'LIKE', date('Y-m') . '%');
        }
        return $query;
    }
}
