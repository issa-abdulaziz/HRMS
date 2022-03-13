<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Overtime;
use App\Models\Attendance;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $currentMonth = date('Y-m');

        $overtimeTotal = Overtime::getTotalOvertimeAmount($currentMonth);
        $leewayTotal = Attendance::getTotalLeewayAmount($currentMonth);
        $absenceTotal = Attendance::getTotalAbsenceAmount($currentMonth);
        $overall = $overtimeTotal - $leewayTotal - $absenceTotal;

        return view('dashboard')->with([
            'overtimeTotal' => $overtimeTotal,
            'absenceTotal' => $absenceTotal,
            'leewayTotal' => $leewayTotal,
            'overall' => $overall,
        ]);
    }

    public function getData(Request $request)
    {
        $months_arr = []; // can't be array, should be collection inorder to use the map function
        for ($t = 0; $t < 12; $t++) {
            $months_arr[] = date("Y-m", strtotime(date('Y-m-01') . " -$t months"));
        }
        $months = collect(array_reverse($months_arr));

        $monthsLabel = $months->map(function ($month, $key) {
            return date('M', strtotime($month));
        });

        $data = $months->map(function ($month, $key) {
            $overtimeTotal = Overtime::getTotalOvertimeAmount($month);
            $leewayTotal = Attendance::getTotalLeewayAmount($month);
            $absenceTotal = Attendance::getTotalAbsenceAmount($month);
            $overall = $overtimeTotal - $leewayTotal - $absenceTotal;
            return [
                'overtimeTotal' => $overtimeTotal,
                'absenceTotal' => $absenceTotal,
                'leewayTotal' => $leewayTotal,
                'overall' => $overall,
            ];
        });

        return response()->json([
            'monthsLabel' => $monthsLabel,
            'data' => $data,
        ]);
    }
}
