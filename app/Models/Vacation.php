<?php

namespace App\models;

use Carbon\Carbon;
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
        $date = now();
        if (isset($params['date']) && trim($params['date'] !== '')) {
            $date = Carbon::parse($params['date']);
            $firstDay = $date->format('Y-m-1');
            $lastDay = $date->format('Y-m-t');
            $query->where(function ($query) use ($firstDay, $lastDay) {
                $query->Where(function ($query) use ($firstDay) {
                    $query->where('date_from', '<=', $firstDay)
                        ->where('date_to', '>=', $firstDay);
                })->orWhere(function ($query) use ($lastDay) {
                    $query->where('date_from', '<=', $lastDay)
                        ->where('date_to', '>=', $lastDay);
                })->orWhere(function ($query) use ($firstDay, $lastDay) {
                    $query->where('date_from', '>=', $firstDay)
                        ->where('date_to', '<=', $lastDay);
                });
            });
        }
        return $query;
    }

}
