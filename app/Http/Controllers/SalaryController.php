<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

    public function index(Request $request)
    {
        $date = $request->has('date') ? $request->date : date('Y-m');
        $employees = auth()->user()->employees()->whereActive(1)->orderBy('full_name', 'asc')->where('hired_at', '<=', \Carbon\Carbon::parse($date)->endOfMonth()->toDateString())->get();

        $data = $employees->map(function ($employee, $key) use ($date) {
            $overtimeAmount = $employee->getOvertimeAmount($date);
            $advancedPaymentAmount = $employee->getAdvancedPaymentAmount($date);
            $absentDayDiscountAmount = $employee->getAbsentDayDiscountAmount($date);
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
        return view('salary.index', compact('data', 'date'));
    }
}
