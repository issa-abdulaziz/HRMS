<?php

namespace App\models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use DateTime;

class Employee extends Model
{
    public $timestamps = false;
    protected $guarded = [];
    protected $appends = ['taking_vacation_start_at', 'can_take_vacation', 'vacation_days', 'hourly_price'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }
    public function overtimes()
    {
        return $this->hasMany(Overtime::class)->orderBy('date', 'desc');
    }
    public function advancedPayments()
    {
        return $this->hasMany(AdvancedPayment::class)->orderBy('date', 'desc');
    }
    public function attendances()
    {
        return $this->hasMany(Attendance::class)->orderBy('date', 'desc');
    }
    public function salaries()
    {
        return $this->hasMany(Salary::class);
    }
    public function vacations()
    {
        return $this->hasMany(Vacation::class)->orderBy('date_to', 'desc');
    }

    public function scopeInVacation($query, $date)
    {
        return $query->whereHas('vacations', function ($query) use ($date) {
            return $query->where('date_from', '<=', $date)
                ->where('date_to', '>=', $date);
        });
    }

    public function getHourlyPriceAttribute()
    {
        return $this->shift ? $this->salary / 30 / $this->shift->working_hour : 0;
    }

    public function getVacationDaysAttribute()
    {
        $diff = Carbon::parse($this->vacation_start_count_at)->diff(today());
        $months = $diff->y * 12 + $diff->m;
        $totalVacations = $months * session('setting')->vacation_rate;
        return ($totalVacations - $this->taken_vacations_days);
    }

    public function getTakingVacationStartAtAttribute()
    {
        return Carbon::parse($this->vacation_start_count_at)->addMonths(session('setting')->taking_vacation_allowed_after);
    }

    public function getCanTakeVacationAttribute()
    {
        return (floor($this->vacation_days) > 0) && (today() >= $this->taking_vacation_start_at);
    }

    public function getOvertimeAmount($month)
    {
        return Overtime::where('date', 'like', $month . '%')->where('employee_id', $this->id)->sum('amount');
    }

    public function getAdvancedPaymentAmount($month)
    {
        return AdvancedPayment::where('date', 'like', $month . '%')->where('employee_id', $this->id)->sum('amount');
    }

    public function getAbsentDay($month)
    {
        return Attendance::where('date', 'like', $month . '%')->where('employee_id', $this->id)->where('present', 'false')->count('present');
    }

    public function getAbsentDayDiscountAmount($month)
    {
        $date = new DateTime($month);
        $firstDay = $date->format('Y-m-1');
        $lastDay = $date->format('Y-m-t');

        $vacations = Vacation::where('employee_id', $this->id)
            ->where(function ($query) use ($firstDay, $lastDay) {
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
            })->get();

        $vacationDays = $vacations->map(function ($vacation) use($firstDay, $lastDay) {
            if ($vacation->date_from <= $firstDay && $vacation->date_to >= $lastDay)
                return diffInDaysExcludingWeekend($firstDay, $lastDay);
            if ($vacation->date_from <= $firstDay && $vacation->date_to <= $lastDay)
                return diffInDaysExcludingWeekend($firstDay, $vacation->date_to);
            if ($vacation->date_from >= $firstDay && $vacation->date_to >= $lastDay)
                return diffInDaysExcludingWeekend($vacation->date_from, $lastDay);
            // if ($vacation->date_from >= $firstDay && $vacation->date_to <= $lastDay)
            return diffInDaysExcludingWeekend($vacation->date_from, $vacation->date_to);
        });

        $totalVacationDays = $vacationDays->sum();
        $absentDays = $this->getAbsentDay($month);
        return number_format((($absentDays - $totalVacationDays) * $this->salary) / 30, 2);
    }

    public function getTotalLeeway($month)
    {
        return Attendance::where('date', 'like', $month . '%')->where('employee_id', $this->id)->sum('total_leeway');
    }

    public function getLeewayDiscount($month)
    {
        return number_format(($this->getTotalLeeway($month) / 60) * session('setting')->leeway_discount_rate * $this->can_take_vacation, 2);
    }

    public function getInTimePercentage()
    {
        $attendances = Attendance::where('employee_id', $this->id)->where('present', '1')->get();
        $overallDays = $attendances->count();
        $inTimeDays = $attendances->where('total_leeway', '0')->count();
        if ($overallDays == 0)
            return '--';
        return number_format($inTimeDays * 100 / $overallDays, 2);
    }
}
