<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use DateTime;

class Employee extends Model
{
    public $timestamps = false;
    protected $guarded = [];

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

    public function getHourlyPrice()
    {
        return $this->shift ? $this->salary / 30 / $this->shift->getWorkingHour() : 0;
    }
    public function getVacationDays()
    {
        $vacationStartCountAt = new DateTime($this->vacation_start_count_at);
        $months = $vacationStartCountAt->diff(today())->y * 12 + $vacationStartCountAt->diff(today())->m;
        $totalVacations = $months * session('setting')->vacation_rate;
        return ($totalVacations - $this->taken_vacations_days);
    }
    public function getTakingVacationStartAt()
    {
        return date('Y-m-d', strtotime("+" . session('setting')->taking_vacation_allowed_after . " months", strtotime($this->vacation_start_count_at)));
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

        $vacationDays = $vacations->map(function ($vacation) {
            return $vacation->diffBtwDate($vacation->date_from, $vacation->date_to);
        });

        $totalVacationDays = $vacationDays->sum();
        $absentDays = $this->getAbsentDay($month);
        if ($absentDays < $totalVacationDays)
            return 0;
        return number_format((($absentDays - $totalVacationDays) * $this->salary) / 30, 2);
    }

    public function getTotalLeeway($month)
    {
        return Attendance::where('date', 'like', $month . '%')->where('employee_id', $this->id)->sum('total_leeway');
    }

    public function getLeewayDiscount($month)
    {
        return number_format(($this->getTotalLeeway($month) / 60) * session('setting')->leeway_discount_rate * $this->getHourlyPrice(), 2);
    }

    public function getInTimePercentage()
    {
        $overallPresentDay = Attendance::where('employee_id', $this->id)->where('present', '1')->count();
        $inTimeDays = Attendance::where('employee_id', $this->id)->where('present', '1')->where('total_leeway', '0')->count();
        if ($overallPresentDay == 0)
            return '--';
        return number_format($inTimeDays * 100 / $overallPresentDay, 2);
    }

    public function canTakeVacation()
    {
        $takingVacationStartAt = new DateTime($this->getTakingVacationStartAt());
        return (floor($this->getVacationDays()) > 0) && (today() >= $takingVacationStartAt);
    }
}
