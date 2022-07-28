<?php

use App\models\Overtime;
use Carbon\Carbon;

function calculateDiffBtw2TimeString($time1, $time2, $crossing_midnight)
{
    $time1 = Carbon::parse($time1);
    $time2 = Carbon::parse($time2);
    $diffInMinutes = $time1->diffInMinutes($time2);
    if ($crossing_midnight)
    {
        $time1InMinutes = $time1->diffInMinutes(today());
        $time2InMinutes = $time2->diffInMinutes(today());

        if ($time1InMinutes + $diffInMinutes !== $time2InMinutes) {
            $diffInMinutes = 24 * 60 - $diffInMinutes;
        }
    }
    return $diffInMinutes;
}

function getTotalLeewayAmount($month) {
    $employees = auth()->user()->employees()->whereActive(1)->get();
    $totalLeewayAmount = $employees->map(function($employee, $key) use ($month) {
        return $employee->getLeewayDiscount($month);
    });
    return $totalLeewayAmount->sum();
}

function getTotalAbsenceAmount($month) {
    $employees = auth()->user()->employees()->whereActive(1)->get();
    $totalAbsenceAmount = $employees->map(function($employee, $key) use ($month) {
        return $employee->getAbsentDayDiscountAmount($month);
    });
    return $totalAbsenceAmount->sum();
}

function getTotalOvertimeAmount($month)
{
    return Overtime::where('date', 'like', $month . '%')
    ->whereHas('employee', fn ($query) => $query->where('user_id', auth()->id()))
    ->sum('amount');
}

function isWeekend($date)
{
    return Carbon::parse($date)->format('l') == session('setting')->weekend;
}

function getDateTo($date, $vacationDays)
{
    $dateFrom = new Carbon($date);

    $daysCollection = collect([]);
    for ($x = 0; $x < $vacationDays; $x++) {
        $daysCollection[] = $dateFrom->copy()->addDays($x)->format('l');
        if ($x == ($vacationDays - 1) && isWeekend($daysCollection->last()))
            $daysCollection[] = $dateFrom->copy()->addDays($x + 1)->format('l');
    }

    $weekends = $daysCollection->filter(function ($item) {
        return isWeekend($item);
    });

    $dateTo = $dateFrom->copy()->addDays($weekends->count() + $vacationDays - 1);
    if (isWeekend($dateTo))
        $dateTo->addDay();
    return $dateTo->format('Y-m-d');
}

function diffInDaysExcludingWeekend($date1, $date2)
{
    $dateFrom = Carbon::parse($date1);
    $dateTo = Carbon::parse($date2);
    $diffInDays = $dateFrom->diffInDays($dateTo);
    $diffInDaysExcludingWeekend = 0;
    for ($x = 0; $x <= $diffInDays; $x++) {
        if (!isWeekend($dateFrom->copy()->addDays($x)->format('l')))
            $diffInDaysExcludingWeekend++;
    }
    return $diffInDaysExcludingWeekend;
}
