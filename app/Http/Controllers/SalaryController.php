<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DateTime;
use App\Models\Employee;
use App\Models\Setting;
use App\Models\Overtime;
use App\Models\AdvancedPayment;
use App\Models\Attendance;

class SalaryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request) {
        $date = $request->has('date') ? $request->date : date('Y-m');
        $employees = Employee::where('active',true)->where('hired_at','<=', \Carbon\Carbon::parse($date)->endOfMonth()->toDateString())->get();
        $setting = Setting::first();

        $data = $employees->map(function($employee, $key) use ($date) {
            $overtimeAmount = $employee->getOvertimeAmount($date);
            $advancedPaymentAmount = $employee->getAdvancedPaymentAmount($date);
            $absentDay = $employee->getAbsentDay($date);
            $absentDayDiscountAmount = $employee->getAbsentDayDiscountAmount($date);
            $totalLeeway = $employee->getTotalLeeway($date);
            $leewayDiscount = $employee->getLeewayDiscount($date);
            $netSalary = $employee->salary + $overtimeAmount - $advancedPaymentAmount - $absentDayDiscountAmount - $leewayDiscount;

            return [
                'employee_id' => $employee->id,
                'employee_name' => $employee->full_name,
                'salary' => $employee->salary,
                'overtimeAmount' => $overtimeAmount,
                'advancedPaymentAmount' => $advancedPaymentAmount,
                'absentDayDiscountAmount' => $absentDayDiscountAmount,
                'leewayDiscount' => $leewayDiscount,
                'netSalary' => $netSalary,
            ];
        });

        // $data = [];
        // foreach ($employees as $employee) {
        //     $overtimeAmount = Overtime::where('date', 'like', $date . '%')->where('employee_id',$employee->id)->sum('amount');
        //     $advancedPaymentAmount = AdvancedPayment::where('date', 'like', $date . '%')->where('employee_id',$employee->id)->sum('amount');
        //     $absentDay = Attendance::where('date', 'like', $date . '%')->where('employee_id',$employee->id)->where('present','false')->count('present');
        //     $absentDayDiscountAmount = number_format(($absentDay * $employee->salary) / 30, 2);
        //     $totalLeeway = Attendance::where('date', 'like', $date . '%')->where('employee_id',$employee->id)->sum('total_leeway');

        //     $starting_time = new DateTime($employee->shift->starting_time);
        //     $leaving_time = new DateTime($employee->shift->leaving_time);
        //     $time_diff = $starting_time->diff($leaving_time);
        //     $work_hour_per_day = $time_diff->h + $time_diff->i /60;
        //     $hourly_price = $employee->salary / 30 / $work_hour_per_day;
        //     $leewayDiscount = number_format(($totalLeeway / 60) * $setting->leeway_discount_rate * $hourly_price, 2);
        //     $netSalary = $employee->salary + $overtimeAmount - $advancedPaymentAmount - $absentDayDiscountAmount - $leewayDiscount;

        //     array_push($data,[
        //         'employee' => $employee->full_name,
        //         'salary' => $employee->salary,
        //         'overtimeAmount' => $overtimeAmount,
        //         'advancedPaymentAmount' => $advancedPaymentAmount,
        //         'absentDayDiscountAmount' => $absentDayDiscountAmount,
        //         'leewayDiscount' => $leewayDiscount,
        //         'netSalary' => $netSalary,
        //     ]);
        // }
        return view('salary.index')->with(['data' => $data, 'currency' => $setting->currency, 'date' => $date]);
    }

}
