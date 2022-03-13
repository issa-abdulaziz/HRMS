<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Shift;
use App\Http\Requests\EmployeeRequest;

class EmployeesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employees = Employee::orderBy('full_name', 'asc')->get();
        return view('employee.index')->with(['employees' => $employees]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $shifts = Shift::select('id', 'title')->get();
        return view('employee.create')->with(['shifts' => $shifts]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmployeeRequest $request)
    {
        $request->validated();

        $employee = new Employee();
        $employee->full_name = $request->full_name;
        $employee->date_of_birth = $request->date_of_birth;
        $employee->city = $request->city;
        $employee->phone_number = $request->phone_number;
        $employee->hired_at = $request->hired_at;
        $employee->position = $request->position;
        $employee->salary = $request->salary;
        $employee->active = $request->has('active') ? 1 : 0;
        $employee->taken_vacations_days = 0;
        $employee->vacation_start_count_at = $request->vacation_start_count_at ? $request->vacation_start_count_at . '-1' : null;

        $shift = Shift::find($request->shift_id);
        $shift->employees()->save($employee);

        return redirect('/employee')->with('success', 'employee Added Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $employee = Employee::findOrFail($id);

        $date = date('Y-m');

        $overtimeAmount = $employee->getOvertimeAmount($date);
        $advancedPaymentAmount = $employee->getAdvancedPaymentAmount($date);
        $absentDay = $employee->getAbsentDay($date);
        $absentDayDiscountAmount = $employee->getAbsentDayDiscountAmount($date);
        $leewayDiscount = $employee->getLeewayDiscount($date);

        $totalLeeway = $employee->getTotalLeeway($date);
        $leewayTime = floor($totalLeeway / 60)  . ':' . $totalLeeway % 60;

        $vacationDays = $employee->getVacationDays();
        $inTimePercentage = $employee->getInTimePercentage();

        return view('employee.show')->with([
            'employee' => $employee,
            'overtimeAmount' => $overtimeAmount,
            'advancedPaymentAmount' => $advancedPaymentAmount,
            'absentDay' => $absentDay,
            'absentDayDiscountAmount' => $absentDayDiscountAmount,
            'leewayTime' => $leewayTime,
            'leewayDiscount' => $leewayDiscount,
            'total' => $overtimeAmount - $advancedPaymentAmount - $absentDayDiscountAmount - $leewayDiscount,
            'vacationDays' => $vacationDays,
            'inTimePercentage' => $inTimePercentage,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        $shifts = Shift::select('id', 'title')->get();
        return view('employee.edit')->with(['employee' => $employee, 'shifts' => $shifts]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EmployeeRequest $request, $id)
    {
        $request->validated();

        $employee = Employee::findOrFail($id);
        $employee->full_name = $request->full_name;
        $employee->date_of_birth = $request->date_of_birth;
        $employee->city = $request->city;
        $employee->phone_number = $request->phone_number;
        $employee->hired_at = $request->hired_at;
        $employee->position = $request->position;
        $employee->salary = $request->salary;
        $employee->active = $request->has('active') ? 1 : 0;
        $employee->vacation_start_count_at = $request->vacation_start_count_at ? $request->vacation_start_count_at . '-1' : null;

        $shift = Shift::find($request->shift_id);
        $shift->employees()->save($employee);

        return redirect('/employee')->with('success', 'employee Edited Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();
        return redirect('/employee')->with('success', 'Employee Deleted Successfully');
    }
    public function getData(Request $request)
    {

        $employee = Employee::findOrFail($request->employee_id);

        $months_arr = []; // can't be array, should be collection inorder to use the map function
        for ($t = 0; $t < 12; $t++) {
            $months_arr[] = date("Y-m", strtotime(date('Y-m-01') . " -$t months"));
        }
        $months = collect(array_reverse($months_arr));

        $monthsLabel = $months->map(function ($month, $key) {
            return date('M', strtotime($month));
        });

        $data = $months->map(function ($month, $key) use ($employee) {

            $overtimeTotal = $employee->getOvertimeAmount($month);
            $absenceTotal = $employee->getAbsentDayDiscountAmount($month);
            $leewayTotal = $employee->getLeewayDiscount($month);
            $advancedPaymentTotal = $employee->getAdvancedPaymentAmount($month);
            $overall = $overtimeTotal - $leewayTotal - $absenceTotal - $advancedPaymentTotal;

            return [
                'overtimeTotal' => $overtimeTotal,
                'absenceTotal' => $absenceTotal,
                'leewayTotal' => $leewayTotal,
                'advancedPaymentTotal' => $advancedPaymentTotal,
                'overall' => $overall,
            ];
        });

        return response()->json([
            'monthsLabel' => $monthsLabel,
            'data' => $data,
        ]);
    }
}
