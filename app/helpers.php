<?php

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

function calculateTotalLeeway($shift, $time_in, $time_out)
{
    $starting_time = Carbon::parse($shift->starting_time);
    $time_in = Carbon::parse($time_in);
    $comming_leeway = $starting_time->diffInMinutes($time_in);

    $leaving_time = Carbon::parse($shift->leaving_time);
    $time_out = Carbon::parse($time_out);
    $leaving_leeway = $leaving_time->diffInMinutes($time_out);

    if ($shift->across_midnight) {
        $starting_time_min = $starting_time->diffInMinutes(today());
        $time_in_min = $time_in->diffInMinutes(today());

        $leaving_time_min = $leaving_time->diffInMinutes(today());
        $time_out_min = $time_out->diffInMinutes(today());

        if ($starting_time_min + $comming_leeway !== $time_in_min) {
            $comming_leeway = 24 * 60 - $comming_leeway;
        }

        if ($time_out_min + $leaving_leeway !== $leaving_time_min) {
            $leaving_leeway = 24 * 60 - $leaving_leeway;
        }
    }
    return $comming_leeway + $leaving_leeway;
}
