<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Vacation extends Model
{
    public $timestamps = false;

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
    public function diffBtwDate($date1, $date2)
    {
        $date1 = $this->date_from;
        $date2 = $this->date_to;
        $diff = strtotime($date1) - strtotime($date2);
        return ceil(abs($diff / 86400)) + 1;
    }

    public static function getDateTo($date, $vacationDays)
    {
        $dateFrom = new Carbon($date);

        $daysCollection = collect([]);
        for ($x = 0; $x < $vacationDays; $x++) {
            $daysCollection[] = $dateFrom->copy()->addDays($x)->format('l');
            if ($x == ($vacationDays - 1) && self::isWeekend($daysCollection->last()))
                $daysCollection[] = $dateFrom->copy()->addDays($x + 1)->format('l');
        }

        $weekends = $daysCollection->filter(function ($item) {
            return self::isWeekend($item);
        });

        $dateTo = $dateFrom->copy()->addDays($weekends->count() + $vacationDays - 1);
        if (self::isWeekend($dateTo))
            $dateTo->addDay();
        return $dateTo->format('Y-m-d');
    }

    public static function isWeekend($date)
    {
        return Carbon::parse($date)->format('l') == session('setting')->weekend;
    }
}
