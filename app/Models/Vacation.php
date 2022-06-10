<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Vacation extends Model
{
    public $timestamps = false;
    protected $guarded = [];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function scopeFilter($query, $params)
    {
        if (isset($params['date']) && trim($params['date'] !== '')) {
            $query->where('date_from', 'LIKE', trim($params['date']) . '%')->orwhere('date_to', 'LIKE', trim($params['date']) . '%');
        } elseif (!isset($params['date'])) {
            $query->where('date_from', 'LIKE', date('Y-m') . '%')->orwhere('date_to', 'LIKE', date('Y-m') . '%');
        }
        return $query;
    }

}
